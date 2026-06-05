<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
use App\Http\Controllers\AdminRideSettingController;
use App\Http\Controllers\AdminAppAppearanceController;
use App\Http\Controllers\AdminDriverMonitorController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminDriverWalletController;
use App\Http\Controllers\AdminFinanceController;
use App\Http\Controllers\AdminVoucherController;
use App\Http\Controllers\CustomerVoucherController;
use App\Http\Controllers\AdminVoucherUsageController;


/*
|--------------------------------------------------------------------------
| CUSTOMER / HOME
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->middleware('auth');

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | UPDATE CUSTOMER LOCATION
    |--------------------------------------------------------------------------
    */

    Route::post('/update-my-location', function (Request $request) {

        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        auth()->user()->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications/count', [NotificationController::class, 'count']);

    /*
    |--------------------------------------------------------------------------
    | CHAT ORDER
    |--------------------------------------------------------------------------
    */

    Route::get('/orders/{order}/chat/{type}', [ChatController::class, 'messages'])
        ->name('orders.chat.messages');

    Route::post('/orders/{order}/chat/{type}/send', [ChatController::class, 'send'])
        ->name('orders.chat.send');

    Route::get('/orders/{order}/chat/{type}/unread', [ChatController::class, 'unreadCount'])
        ->name('orders.chat.unread');   
    Route::get(
    '/orders/{order}/chat/{type}/badge',
    [ChatController::class, 'badge']
);

    Route::get('/chat-image/{id}', [ChatController::class, 'image'])
    ->name('chat.image');


    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */

    Route::get('/cart', [CartController::class, 'index']);
    Route::get('/cart/add/{id}', [CartController::class, 'add']);
    Route::get('/cart/remove/{id}', [CartController::class, 'remove']);
    Route::get('/cart/clear', [CartController::class, 'clear']);
    Route::get('/cart/increase/{id}', [CartController::class, 'increase']);
    Route::get('/cart/decrease/{id}', [CartController::class, 'decrease']);

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT FOOD
    |--------------------------------------------------------------------------
    */

    Route::get('/checkout', [OrderController::class, 'checkoutPage'])
        ->name('checkout.page');

    Route::post('/checkout/calculate', [OrderController::class, 'calculateCheckout'])
        ->name('checkout.calculate');

    Route::post('/checkout/order', [OrderController::class, 'storeOrder'])
        ->name('checkout.order');

    /*
    |--------------------------------------------------------------------------
    | OJEK / RIDE / CAR
    |--------------------------------------------------------------------------
    */

    Route::get('/ojek', [OrderController::class, 'ojekPage'])
        ->name('ojek.page');

    Route::post('/ojek/calculate', [OrderController::class, 'calculateOjek'])
        ->name('ojek.calculate');

    Route::post('/ojek/order', [OrderController::class, 'storeOjekOrder'])
        ->name('ojek.order');

    Route::get('/car', [OrderController::class, 'carPage'])
        ->name('car.page');

    Route::post('/car/calculate', [OrderController::class, 'calculateCar'])
        ->name('car.calculate');

    Route::post('/car/order', [OrderController::class, 'storeCarOrder'])
        ->name('car.order');

    Route::get('/active-drivers', [HomeController::class, 'activeDrivers']);

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER ORDER
    |--------------------------------------------------------------------------
    */

    Route::get('/customer/vouchers', [
        CustomerVoucherController::class,
        'index'
    ])->name('customer.vouchers');

    Route::post('/orders/{id}/rating', [OrderController::class, 'storeRating'])
    ->name('orders.rating');
    Route::get('/my-orders', [CartController::class, 'orders']);

    Route::get('/my-orders/history', [CartController::class, 'orderHistory'])
        ->name('orders.history');

    Route::get('/my-orders/{id}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::get('/order/{id}/driver-location', [OrderController::class, 'driverLocation'])
        ->name('orders.driver.location');

    /*
    |--------------------------------------------------------------------------
    | MERCHANT FOODS
    |--------------------------------------------------------------------------
    */

    Route::get('/customer', function () {
        return redirect('/');
    });

    Route::get('/merchant/{id}/foods', [HomeController::class, 'merchantFoods'])
        ->name('merchant.foods');

    /*
    |--------------------------------------------------------------------------
    | APPLY DRIVER
    |--------------------------------------------------------------------------
    */

    Route::get('/apply-driver', [DriverApplicationController::class, 'create']);
    Route::post('/apply-driver', [DriverApplicationController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | APPLY MERCHANT
    |--------------------------------------------------------------------------
    */

    Route::get('/apply-merchant', [MerchantController::class, 'create']);
    Route::post('/apply-merchant', [MerchantController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION SETTING
    |--------------------------------------------------------------------------
    */

    Route::post('/notification-setting/update', [NotificationSettingController::class, 'update'])
        ->name('notification.setting.update');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['role.redirect:admin'])->prefix('admin')->group(function () {

    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/finance', [AdminFinanceController::class, 'index'])
    ->name('admin.finance.index');
Route::get('/vouchers', [AdminVoucherController::class,'index']);
Route::get('/vouchers/create', [AdminVoucherController::class,'create']);
Route::post('/vouchers/store', [AdminVoucherController::class,'store']);

Route::get('/vouchers/{id}/edit', [AdminVoucherController::class,'edit']);
Route::post('/vouchers/{id}/update', [AdminVoucherController::class,'update']);
Route::get('/vouchers/{id}/delete', [AdminVoucherController::class,'delete']);

Route::get('/vouchers/{id}/toggle', [AdminVoucherController::class,'toggle']);

Route::get('/voucher-usages', [AdminVoucherUsageController::class, 'index']);


    /*
    |--------------------------------------------------------------------------
    | ADMIN USERS
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [AdminUserController::class, 'index']);
    Route::post('/users/{id}/role', [AdminUserController::class, 'updateRole']);
    Route::get('/users/{id}/delete', [AdminUserController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | ADMIN ORDERS
    |--------------------------------------------------------------------------
    */

    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/history', [AdminOrderController::class, 'history']);
    Route::get('/orders/{id}/status/{status}', [AdminOrderController::class, 'updateStatus']);
    Route::post('/orders/{id}/assign-driver', [AdminOrderController::class, 'assignDriver']);

    /*
    |--------------------------------------------------------------------------
    | ADMIN DRIVER
    |--------------------------------------------------------------------------
    */
    Route::get('/driver-wallet', [AdminDriverWalletController::class, 'index'])
    ->name('admin.driver-wallet.index');

    Route::post('/driver-wallet/{driver}/store', [AdminDriverWalletController::class, 'store'])
    ->name('admin.driver-wallet.store');

    Route::get('/drivers', [AdminDriverController::class, 'index']);
    Route::get('/drivers/create', [AdminDriverController::class, 'create']);
    Route::post('/drivers/store', [AdminDriverController::class, 'store']);

    Route::get('/drivers/stopped', [AdminDriverController::class, 'stopped']);
    Route::get('/drivers/penalty', [AdminDriverController::class, 'penaltyList']);

    Route::get('/drivers/{id}/stop', [AdminDriverController::class, 'stop']);
    Route::post('/drivers/{id}/penalty', [AdminDriverController::class, 'penalty']);
    Route::get('/drivers/{id}/clear-penalty', [AdminDriverController::class, 'clearPenalty']);

    Route::get('/driver-monitor', [AdminDriverMonitorController::class, 'index'])
        ->name('admin.driver.monitor');

    Route::get('/driver-monitor/data', [AdminDriverMonitorController::class, 'data'])
        ->name('admin.driver.monitor.data');

    /*
    |--------------------------------------------------------------------------
    | DRIVER APPLICATION
    |--------------------------------------------------------------------------
    */

    Route::get('/driver-applications', [DriverApplicationController::class, 'adminIndex']);
    Route::get('/driver-applications/{id}/approve', [DriverApplicationController::class, 'approve']);
    Route::get('/driver-applications/{id}/reject', [DriverApplicationController::class, 'reject']);

    /*
    |--------------------------------------------------------------------------
    | MERCHANT APPLICATION
    |--------------------------------------------------------------------------
    */

    Route::get('/merchant-applications', [AdminMerchantController::class, 'index']);
    Route::get('/merchant-applications/{id}/approve', [AdminMerchantController::class, 'approve']);
    Route::get('/merchant-applications/{id}/reject', [AdminMerchantController::class, 'reject']);

    /*
    |--------------------------------------------------------------------------
    | SETTINGS
    |--------------------------------------------------------------------------
    */

    Route::get('/delivery-setting', [AdminDeliverySettingController::class, 'edit']);
    Route::post('/delivery-setting', [AdminDeliverySettingController::class, 'update']);

    Route::get('/ride-setting', [AdminRideSettingController::class, 'edit']);
    Route::post('/ride-setting', [AdminRideSettingController::class, 'update']);

    Route::get('/app-appearance', [AdminAppAppearanceController::class, 'edit']);
    Route::post('/app-appearance', [AdminAppAppearanceController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| RESTAURANT & FOOD ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['role.redirect:admin'])->group(function () {

    Route::get('/restaurants', [RestaurantController::class, 'index']);
    Route::get('/restaurants/create', [RestaurantController::class, 'create']);
    Route::post('/restaurants/store', [RestaurantController::class, 'store']);
    Route::get('/restaurants/{id}/edit', [RestaurantController::class, 'edit']);
    Route::post('/restaurants/{id}/update', [RestaurantController::class, 'update']);
    Route::get('/restaurants/{id}/delete', [RestaurantController::class, 'destroy']);

    Route::get('/foods', [FoodController::class, 'index']);
    Route::get('/foods/create', [FoodController::class, 'create']);
    Route::post('/foods/store', [FoodController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| DRIVER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('driver')->group(function () {

    Route::get('/', [DriverController::class, 'dashboard']);

    Route::get('/notif-count', [DriverController::class, 'notifCount']);

    Route::get('/status/{status}', [DriverController::class, 'setStatus']);

    Route::get('/order/{id}/accept', [DriverController::class, 'acceptOrder']);
    Route::get('/order/{id}/reject', [DriverController::class, 'rejectOrder']);
    Route::get('/order/{id}/status/{status}', [DriverController::class, 'updateOrderStatus']);

    Route::post('/location/update', [DriverController::class, 'updateLocation']);

    Route::get('/history', [DriverController::class, 'history']);

    Route::get('/settings', [DriverController::class, 'settings']);
    Route::post('/settings', [DriverController::class, 'updateSettings']);

    Route::post('/vehicles/add', [DriverController::class, 'addVehicle'])
        ->name('driver.vehicles.add');

    Route::get('/vehicles/{id}/active', [DriverController::class, 'setActiveVehicle'])
        ->name('driver.vehicles.active');

    Route::get('/vehicles/{id}/delete', [DriverController::class, 'deleteVehicle'])
        ->name('driver.vehicles.delete');

    Route::get('/active-locations', [DriverController::class, 'activeLocations']);

    Route::get('/wallet-history', [DriverController::class, 'walletHistory'])
    ->name('driver.wallet-history');

    Route::get('/income', [DriverController::class, 'income'])
    ->name('driver.income');

    });

/*
|--------------------------------------------------------------------------
| MERCHANT
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('merchant')->group(function () {

    Route::get('/', [MerchantController::class, 'dashboard']);

    Route::get('/notif-count', [MerchantController::class, 'notifCount']);

    Route::get('/foods', [MerchantController::class, 'foods']);
    Route::get('/foods/create', [MerchantController::class, 'createFood']);
    Route::post('/foods/store', [MerchantController::class, 'storeFood']);

    Route::get('/orders/{id}/accept', [MerchantController::class, 'acceptOrder']);
    Route::get('/orders/{id}/reject', [MerchantController::class, 'rejectOrder']);

    Route::post('/restaurants/{id}/hours', [MerchantController::class, 'updateHours']);
    Route::get('/restaurants/{id}/edit', [MerchantController::class, 'editRestaurant']);
    Route::post('/restaurants/{id}/update', [MerchantController::class, 'updateRestaurant']);
    Route::get('/restaurants/{id}/toggle-open', [MerchantController::class, 'toggleOpen']);
    Route::get('/finance', [MerchantController::class, 'finance'])
    ->name('merchant.finance');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';