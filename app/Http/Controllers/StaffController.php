<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard()
    {
        $orders = Order::with('items.menu', 'user')
            ->whereDate('created_at', today())
            ->orderBy('pickup_time', 'desc')
            ->get();
        $menus = Menu::all();
        $seats = \App\Models\Seat::all();
        
        return view('staff.dashboard', compact('orders', 'menus', 'seats'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $order->update(['order_status' => $request->status]);
        return back()->with('success', 'Order status updated successfully!');
    }

    public function updateStock(Request $request, Menu $menu)
    {
        $menu->stock = $request->stock;
        $menu->portions_left = $request->stock;
        $menu->updateStatus();
        return back()->with('success', 'Menu stock updated successfully!');
    }

    public function updateSeatStatus(Request $request, \App\Models\Seat $seat)
    {
        $request->validate([
            'available_seats' => 'required|integer|min:0|max:'.$seat->capacity,
        ]);
        
        $status = $request->status ?? 'available';
        if ($request->available_seats == 0) {
            $status = 'full';
        }

        $seat->update([
            'available_seats' => $request->available_seats,
            'status' => $status,
            'social_mode' => $request->has('social_mode') ? true : false,
        ]);

        return back()->with('success', 'Seat table status updated successfully!');
    }
}
