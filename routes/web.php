<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminDriverController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverApplicationController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminMerchantController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationSettingController;
use App\Http\Controllers\AdminDeliverySettingController;


/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->middleware('auth');
Route::get('/dashboard', function () { return redirect('/');})
->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/count', [NotificationController::class, 'count']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::get('/cart/add/{id}', [CartController::class, 'add']);
    Route::get('/cart/remove/{id}', [CartController::class, 'remove']);
    Route::get('/cart/clear', [CartController::class, 'clear']);
    Route::get('/checkout', [CartController::class, 'checkout']);
    Route::get('/my-orders', [CartController::class, 'orders']);
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/customer', function () { return redirect('/');  });
    Route::get('/apply-driver', [DriverApplicationController::class, 'create']);
    Route::post('/apply-driver', [DriverApplicationController::class, 'store']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/apply-merchant', [MerchantController::class, 'create']);
    Route::post('/apply-merchant', [MerchantController::class, 'store']);
    Route::post('/notification-setting/update', [NotificationSettingController::class, 'update'])
    ->name('notification.setting.update');
    Route::get('/cart/increase/{id}', [CartController::class, 'increase']);
    Route::get('/cart/decrease/{id}', [CartController::class, 'decrease']);


    });

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['role.redirect:admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'dashboard']);
    Route::get('/admin/drivers/stopped', [AdminDriverController::class, 'stopped']);
    Route::get('/admin/drivers/penalty', [AdminDriverController::class, 'penaltyList']);
    Route::get('/admin/users', [AdminUserController::class, 'index']);
    Route::post('/admin/users/{id}/role', [AdminUserController::class, 'updateRole']);
    Route::get('/admin/driver-applications', [DriverApplicationController::class, 'adminIndex']);
    Route::get('/admin/driver-applications/{id}/approve', [DriverApplicationController::class, 'approve']);
    Route::get('/admin/driver-applications/{id}/reject', [DriverApplicationController::class, 'reject']);
    Route::get('/admin/orders', [AdminOrderController::class, 'index']);
    Route::get('/admin/orders/{id}/status/{status}', [AdminOrderController::class, 'updateStatus']);
    Route::post('/admin/orders/{id}/assign-driver', [AdminOrderController::class, 'assignDriver']);
    Route::get('/admin/drivers/{id}/stop', [AdminDriverController::class, 'stop']);
    Route::post('/admin/drivers/{id}/penalty', [AdminDriverController::class, 'penalty']);
    Route::get('/admin/drivers/{id}/clear-penalty', [AdminDriverController::class, 'clearPenalty']);
    Route::get('/admin/drivers', [AdminDriverController::class, 'index']);
    Route::get('/admin/drivers/create', [AdminDriverController::class, 'create']);
    Route::post('/admin/drivers/store', [AdminDriverController::class, 'store']);
    Route::get('/admin/users/{id}/delete', [AdminUserController::class, 'destroy']);
    Route::get('/restaurants', [RestaurantController::class, 'index']);
    Route::get('/restaurants/create', [RestaurantController::class, 'create']);
    Route::post('/restaurants/store', [RestaurantController::class, 'store']);
    Route::get('/restaurants/{id}/edit', [RestaurantController::class, 'edit']);
    Route::post('/restaurants/{id}/update', [RestaurantController::class, 'update']);
    Route::get('/restaurants/{id}/delete', [RestaurantController::class, 'destroy']);
    Route::get('/admin/merchant-applications', [AdminMerchantController::class, 'index']);
    Route::get('/admin/merchant-applications/{id}/approve', [AdminMerchantController::class, 'approve']);
    Route::get('/admin/merchant-applications/{id}/reject', [AdminMerchantController::class, 'reject']);
    Route::get('/foods', [FoodController::class, 'index']);
    Route::get('/foods/create', [FoodController::class, 'create']);
    Route::post('/foods/store', [FoodController::class, 'store']);
    Route::get('/admin/delivery-setting', [AdminDeliverySettingController::class, 'edit']);
    Route::post('/admin/delivery-setting', [AdminDeliverySettingController::class, 'update']);

});

/*
|--------------------------------------------------------------------------
| DRIVER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    Route::get('/driver/notif-count', [DriverController::class, 'notifCount']);
    Route::get('/driver', [DriverController::class, 'dashboard']);
    Route::get('/driver/order/{id}/status/{status}', [DriverController::class, 'updateOrderStatus']);
    Route::get('/driver/status/{status}', [DriverController::class, 'setStatus']);
    Route::get('/driver/order/{id}/accept', [DriverController::class, 'acceptOrder']);
    Route::get('/driver/order/{id}/reject', [DriverController::class, 'rejectOrder']);
    Route::post('/driver/location/update', [DriverController::class, 'updateLocation']);

});


/*
|--------------------------------------------------------------------------
| MERCHANT
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/merchant', [MerchantController::class, 'dashboard']);
    Route::post('/merchant/restaurants/{id}/hours', [MerchantController::class, 'updateHours']);
    Route::get('/merchant/foods/create', [MerchantController::class, 'createFood']);
    Route::post('/merchant/foods/store', [MerchantController::class, 'storeFood']);
    Route::get('/merchant/orders/{id}/accept', [MerchantController::class, 'acceptOrder']);
    Route::get('/merchant/orders/{id}/reject', [MerchantController::class, 'rejectOrder']);
    Route::get('/merchant/restaurants/{id}/edit', [MerchantController::class, 'editRestaurant']);
    Route::post('/merchant/restaurants/{id}/update', [MerchantController::class, 'updateRestaurant']);
    Route::get('/merchant/restaurants/{id}/toggle-open', [MerchantController::class, 'toggleOpen']);
    Route::get('/merchant/notif-count', [MerchantController::class, 'notifCount']);
    Route::get('/merchant/foods', [MerchantController::class, 'foods']);

    });

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';