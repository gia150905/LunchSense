<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Student routes
    Route::get('/home', [MenuController::class, 'home'])->name('home');
    Route::get('/menu', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/order/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/order', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    
    // Seats reservation
    Route::get('/seats', [SeatController::class, 'index'])->name('seats.index');
    Route::post('/seats/{seat}/reserve', [SeatController::class, 'reserve'])->name('seats.reserve');
    Route::post('/seats/release', [SeatController::class, 'release'])->name('seats.release');
    Route::get('/seats/directions', [SeatController::class, 'directions'])->name('seats.directions');

    // Digital Wallet
    Route::get('/wallet/topup', [WalletController::class, 'topup'])->name('wallet.topup');
    Route::post('/wallet/checkout', [WalletController::class, 'checkout'])->name('wallet.checkout');
    Route::get('/wallet/history', [WalletController::class, 'history'])->name('wallet.history');

    // Notifications
    Route::get('/notifications', function () {
        return view('notifications.index');
    })->name('notifications.index');

    // Profile Routes from Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard redirection depending on role
    Route::get('/dashboard', function () {
        if (in_array(auth()->user()->role, ['staff', 'admin'])) {
            return redirect()->route('staff.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    // Staff routes
    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
        Route::post('/order/{order}/status', [StaffController::class, 'updateOrderStatus'])->name('order.status');
        Route::post('/menu/{menu}/stock', [StaffController::class, 'updateStock'])->name('menu.stock');
        Route::post('/seat/{seat}/status', [StaffController::class, 'updateSeatStatus'])->name('seat.status');
        Route::resource('/menus', MenuController::class)->except(['index', 'show']);
        Route::resource('/seats', SeatController::class)->except(['index', 'show']);
    });
});

require __DIR__.'/auth.php';
