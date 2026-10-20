<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\InventoryTransactionController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoomBookingController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\TableBookingController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('inventories', InventoryController::class);
    Route::resource('banners', BannerController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('tables', TableController::class);
    Route::resource('table-bookings', TableBookingController::class);
    Route::resource('room-bookings', RoomBookingController::class);
    Route::resource('users', UserController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('inventory-transactions', InventoryTransactionController::class);

    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos', [POSController::class, 'store'])->name('pos.store');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/daily', [ReportController::class, 'dailyReport'])->name('daily');
        Route::get('/daily/export', [ReportController::class, 'exportDailyReport'])->name('daily.export');
        Route::get('/monthly', [ReportController::class, 'monthlyReport'])->name('monthly');
    });
});

require __DIR__.'/auth.php';
