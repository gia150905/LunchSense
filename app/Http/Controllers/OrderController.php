<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $menus = Menu::where('status', '!=', 'sold_out')->get();
        return view('orders.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'          => 'required|array',
            'pickup_time'    => 'required',
            'payment_method' => 'required|string',
        ]);

        $total = 0;
        $itemsToOrder = [];
        
        foreach ($request->items as $menuId => $qty) {
            if ($qty < 1) continue;
            $menu = Menu::findOrFail($menuId);
            $subtotal = $menu->price * $qty;
            $total += $subtotal;
            $itemsToOrder[] = [
                'menu' => $menu,
                'qty' => $qty,
                'subtotal' => $subtotal
            ];
        }

        if (empty($itemsToOrder)) {
            return back()->with('error', 'Your cart is empty!');
        }

        $serviceFee = 0.50;
        $grandTotal = $total + $serviceFee;

        $user = auth()->user();

        // Check wallet balance if user pays via digital wallet
        if ($request->payment_method === 'LunchSense Wallet') {
            if ($user->wallet_balance < $grandTotal) {
                return back()->with('error', 'Insufficient wallet balance! Please top up your wallet.');
            }
            // Deduct from wallet
            $user->wallet_balance -= $grandTotal;
            $user->save();
        }

        // Randomize pickup counter (Counter A or Counter B)
        $counters = ['Counter A', 'Counter B'];
        $pickupCounter = $counters[array_rand($counters)];

        $order = Order::create([
            'user_id'        => $user->id,
            'total_price'    => $grandTotal,
            'pickup_time'    => $request->pickup_time,
            'payment_status' => 'paid',
            'order_status'   => 'waiting',
            'pickup_counter' => $pickupCounter,
            'payment_method' => $request->payment_method,
            'service_fee'    => $serviceFee,
        ]);

        foreach ($itemsToOrder as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id'  => $item['menu']->id,
                'quantity' => $item['qty'],
                'subtotal' => $item['subtotal'],
            ]);

            // Decrement stock and portions_left
            $item['menu']->stock -= $item['qty'];
            $item['menu']->updateStatus();
        }

        $order->update([
            'qr_code' => 'LUNCHSENSE-' . $order->id . '-' . $user->id,
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id() && auth()->user()->role === 'student') {
            abort(403);
        }
        $qr = QrCode::size(200)->generate($order->qr_code);
        return view('orders.show', compact('order', 'qr'));
    }
}
