<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index()
    {
        $seats = Seat::all();
        $totalSeats = Seat::sum('capacity');
        $availableSeatsCount = Seat::sum('available_seats');
        
        $activeReservation = session('active_reservation');
        $reservedSeat = null;
        if ($activeReservation) {
            $reservedSeat = Seat::find($activeReservation['table_id']);
        }
        
        return view('seats.index', compact('seats', 'totalSeats', 'availableSeatsCount', 'activeReservation', 'reservedSeat'));
    }

    public function reserve(Request $request, Seat $seat)
    {
        if (session('active_reservation')) {
            return back()->with('error', 'You already have an active seat reservation!');
        }

        if ($seat->available_seats <= 0) {
            return back()->with('error', 'No seats available at this table!');
        }

        // Decrement available seats
        $seat->available_seats -= 1;
        if ($seat->available_seats == 0) {
            $seat->status = 'full';
        }
        $seat->save();

        // Determine a seat letter (e.g., Seat A, B, C, D)
        $seatLetters = ['A', 'B', 'C', 'D'];
        $currentUsers = $seat->current_users ?? [];
        if (is_string($currentUsers)) {
            $currentUsers = json_decode($currentUsers, true) ?? [];
        }
        $occupiedCount = count($currentUsers);
        $seatLetter = $seatLetters[$occupiedCount % 4] ?? 'A';

        // Add to active reservation in session
        session(['active_reservation' => [
            'table_id' => $seat->id,
            'table_number' => $seat->table_number,
            'zone' => $seat->zone,
            'seat_label' => $seatLetter,
            'valid_until' => now()->addHours(2)->format('h:i A'),
            'table_mates' => $currentUsers,
        ]]);

        return back()->with('success', 'Seat reserved successfully!');
    }

    public function release(Request $request)
    {
        $reservation = session('active_reservation');
        if ($reservation) {
            $seat = Seat::find($reservation['table_id']);
            if ($seat) {
                $seat->available_seats = min($seat->capacity, $seat->available_seats + 1);
                if ($seat->status == 'full') {
                    $seat->status = 'available';
                }
                $seat->save();
            }
            session()->forget('active_reservation');
        }

        return redirect()->route('seats.index')->with('success', 'Reservation released!');
    }

    public function directions()
    {
        $activeReservation = session('active_reservation');
        if (!$activeReservation) {
            return redirect()->route('seats.index');
        }
        return view('seats.directions', compact('activeReservation'));
    }
}
