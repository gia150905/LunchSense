<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $search = $request->query('search');
        
        $query = Menu::query();
        
        if ($category && $category !== 'Popular') {
            $query->where('category', $category);
        }
        
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        
        $menus = $query->get();
        
        return view('menus.index', compact('menus', 'category', 'search'));
    }

    public function home()
    {
        // Seating Status Calculations
        $totalSeats = \App\Models\Seat::sum('capacity');
        $availableSeats = \App\Models\Seat::sum('available_seats');
        $seatAvailabilityPercent = $totalSeats > 0 ? round(($availableSeats / $totalSeats) * 100) : 0;
        
        // Food Availability Calculations
        $totalPortions = Menu::sum('portions_left');
        $foodAvailabilityPercent = 85; // Default mockup matching Figma
        if ($totalPortions > 0) {
            $foodAvailabilityPercent = min(100, round(($totalPortions / 100) * 100));
        }
        
        $crowdStatus = $this->getCrowdStatus();
        
        // Seeding some eco statistics and forecasts
        $ecoImpact = [
            'co2' => '32 kg',
            'water' => '18%',
            'packaging' => '94%'
        ];

        $forecast = [
            '11:00 AM' => 30,
            '12:00 PM' => 85,
            '01:00 PM' => 95,
            '02:00 PM' => 50,
            '03:00 PM' => 15
        ];

        return view('menus.home', compact(
            'crowdStatus', 
            'availableSeats', 
            'seatAvailabilityPercent', 
            'foodAvailabilityPercent',
            'ecoImpact',
            'forecast'
        ));
    }

    private function getCrowdStatus()
    {
        $orderCount = Order::whereDate('created_at', today())
            ->whereBetween('created_at', [now()->subHour(), now()])
            ->count();

        if ($orderCount >= 40) return ['label' => 'Very Crowded', 'color' => 'danger', 'wait_time' => '15 mins', 'best_arrival' => '2:00 PM'];
        if ($orderCount >= 20) return ['label' => 'Crowded', 'color' => 'accent', 'wait_time' => '8 mins', 'best_arrival' => '1:45 PM'];
        if ($orderCount >= 10) return ['label' => 'Moderate', 'color' => 'yellow', 'wait_time' => '5 mins', 'best_arrival' => '1:30 PM'];
        return ['label' => 'Low', 'color' => 'primary', 'wait_time' => '2 mins', 'best_arrival' => '1:30 PM'];
    }
}
