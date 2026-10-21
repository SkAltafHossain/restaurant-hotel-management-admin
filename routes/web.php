<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\InventoryTransactionController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoomBookingController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\Admin\TableBookingController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaiterController;
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
    Route::get('/api/items', [ItemController::class, 'apiIndex'])->name('api.items');
    Route::get('/', [DashboardController::class, 'index'])->middleware('can:view dashboard')->name('dashboard');

    Route::resource('categories', CategoryController::class)->middleware('can:manage categories');
    Route::resource('items', ItemController::class)->middleware('can:manage items');
    Route::resource('rooms', RoomController::class)->middleware('can:manage rooms');
    Route::resource('inventories', InventoryController::class)->middleware('can:manage inventory');
    Route::resource('banners', BannerController::class)->middleware('can:manage banners');
    Route::resource('orders', OrderController::class)->middleware('can:manage orders');
    Route::post('/orders/payment', [OrderController::class, 'payment'])->name('orders.payment')->middleware('can:manage orders');
    Route::post('/orders/add-item', [OrderController::class, 'addItem'])->name('orders.add-item')->middleware('can:manage orders');
    Route::post('/orders/{order}/start-preparing', [OrderController::class, 'startPreparing'])->name('orders.start-preparing')->middleware('can:manage orders');
    Route::post('/orders/{order}/mark-ready', [OrderController::class, 'markReady'])->name('orders.mark-ready')->middleware('can:manage orders');
    Route::post('/orders/{order}/mark-out-for-delivery', [OrderController::class, 'markOutForDelivery'])->name('orders.mark-out-for-delivery')->middleware('can:manage orders');
    Route::post('/orders/{order}/mark-completed', [OrderController::class, 'markCompleted'])->name('orders.mark-completed')->middleware('can:manage orders');
    Route::post('/orders/{order}/assign', [OrderController::class, 'assignOrder'])->name('orders.assign')->middleware('can:manage orders');
    Route::resource('tables', TableController::class)->middleware('can:manage categories');
    Route::resource('table-bookings', TableBookingController::class)->middleware('can:manage table bookings');
    Route::resource('room-bookings', RoomBookingController::class)->middleware('can:manage room bookings');
    Route::resource('users', UserController::class)->middleware('can:manage users');
    Route::resource('expenses', ExpenseController::class)->only(['index', 'store', 'update', 'destroy'])->middleware('can:manage expenses');
    Route::resource('salaries', SalaryController::class)->only(['index', 'store', 'update', 'destroy', 'show'])->middleware('can:manage salaries');
    Route::resource('holidays', HolidayController::class)->only(['index', 'store', 'update', 'destroy'])->middleware('can:manage holidays');
    Route::resource('inventory-transactions', InventoryTransactionController::class)->middleware('can:manage inventory');

    Route::get('/pos', [POSController::class, 'index'])->middleware('can:use pos')->name('pos.index');
    Route::post('/pos', [POSController::class, 'store'])->middleware('can:use pos')->name('pos.store');

    Route::prefix('reports')->name('reports.')->middleware('can:generate reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/daily', [ReportController::class, 'dailyReport'])->name('daily');
        Route::get('/daily/export', [ReportController::class, 'exportDailyReport'])->name('daily.export');
        Route::get('/monthly', [ReportController::class, 'monthlyReport'])->name('monthly');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::middleware('kitchen.staff')->prefix('kitchen')->name('kitchen.')->group(function () {
        Route::get('/', [KitchenController::class, 'index'])->name('index');
        Route::post('/orders/{order}/start-preparing', [KitchenController::class, 'startPreparing'])->name('start-preparing');
        Route::post('/orders/{order}/mark-ready', [KitchenController::class, 'markReady'])->name('mark-ready');
    });

    Route::middleware('waiter')->prefix('waiter')->name('waiter.')->group(function () {
        Route::get('/', [WaiterController::class, 'index'])->name('index');
        Route::post('/orders/{order}/mark-served', [WaiterController::class, 'markServed'])->name('mark-served');
        Route::get('/table/{table}/orders', [WaiterController::class, 'getTableOrders'])->name('table.orders');
    });

    Route::middleware('delivery.boy')->prefix('delivery')->name('delivery.')->group(function () {
        Route::get('/', [DeliveryController::class, 'index'])->name('index');
        Route::post('/orders/{order}/assign', [DeliveryController::class, 'assignOrder'])->name('assign');
        Route::post('/orders/{order}/mark-out-for-delivery', [DeliveryController::class, 'markOutForDelivery'])->name('mark-out-for-delivery');
        Route::post('/orders/{order}/mark-delivered', [DeliveryController::class, 'markDelivered'])->name('mark-delivered');
    });
});

require __DIR__.'/auth.php';
