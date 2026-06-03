<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function topup()
    {
        $user = auth()->user();
        return view('wallet.topup', compact('user'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string'
        ]);

        $amount = (float) $request->amount;
        $user = auth()->user();
        
        // Add to wallet balance
        $user->wallet_balance += $amount;
        $user->save();

        // Log transaction
        $logs = session('wallet_logs', $this->getInitialLogs());
        array_unshift($logs, [
            'reference' => 'TXN-' . strtoupper(bin2hex(random_bytes(4))),
            'date' => now()->format('d M Y, h:i A'),
            'type' => 'Top Up',
            'payment_method' => $request->payment_method,
            'amount' => $amount,
            'status' => 'Success'
        ]);
        session(['wallet_logs' => $logs]);

        return redirect()->route('wallet.topup')->with('success', 'RM ' . number_format($amount, 2) . ' topped up successfully!');
    }

    public function history()
    {
        $logs = session('wallet_logs', $this->getInitialLogs());
        return view('wallet.history', compact('logs'));
    }

    private function getInitialLogs()
    {
        return [
            [
                'reference' => 'TXN-A3B9C2D1',
                'date' => now()->subDays(1)->format('d M Y, h:i A'),
                'type' => 'Deduction (Nasi Ayam Order)',
                'payment_method' => 'LunchSense Wallet',
                'amount' => -6.50,
                'status' => 'Success'
            ],
            [
                'reference' => 'TXN-F8E7D6C5',
                'date' => now()->subDays(3)->format('d M Y, h:i A'),
                'type' => 'Top Up',
                'payment_method' => 'DuitNow QR',
                'amount' => 50.00,
                'status' => 'Success'
            ],
            [
                'reference' => 'TXN-K4J3H2G1',
                'date' => now()->subDays(5)->format('d M Y, h:i A'),
                'type' => 'Deduction (Mee Goreng Order)',
                'payment_method' => 'LunchSense Wallet',
                'amount' => -5.50,
                'status' => 'Success'
            ],
            [
                'reference' => 'TXN-M9L8K7J6',
                'date' => now()->subDays(7)->format('d M Y, h:i A'),
                'type' => 'Top Up',
                'payment_method' => 'Touch \'n Go',
                'amount' => 20.00,
                'status' => 'Success'
            ]
        ];
    }
}
