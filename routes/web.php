<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// -------------------- Frontend Controllers --------------------
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SupervisorController;
use App\Http\Controllers\Customer\AddressController;

// -------------------- Admin Controllers -----------------------
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Admin\UserAddressController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Admin\ShippingMethodController;
use App\Http\Controllers\Admin\AccountSettingsController;
use App\Http\Controllers\Admin\ShippingCompanyController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Frontend\OmnipayPaymentController;
use App\Http\Controllers\Customer\CustomerInvoiceController;
use App\Http\Controllers\Frontend\FrontNotificationController;

use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Frontend\HomeController as FrontHomeController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;

// -------------------- Supervisor Controllers ------------------
use App\Http\Controllers\Frontend\ReviewController as FrontReviewController;

// -------------------- Customer Controllers --------------------
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Frontend\ProductController as FrontProductController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboardController;

/*
|--------------------------------------------------------------------------
| 1) Public Frontend Home (صفحة الستور الرئيسية)
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontHomeController::class, 'index'])
    ->name('frontend.home');

/*
|--------------------------------------------------------------------------
| 2) Payment Routes
|--------------------------------------------------------------------------
*/
Route::get('/payment/paypal/callback', [PaymentController::class, 'handlePayPalCallback'])
    ->name('payment.paypal.callback');

Route::get('/payment/paypal/cancel', [PaymentController::class, 'handlePayPalCancel'])
    ->name('payment.paypal.cancel');

Route::get('/payment/omnipay/callback', [OmnipayPaymentController::class, 'callback'])
    ->name('payment.omnipay.callback');

Route::middleware('auth')->group(function () {
    Route::get('/payment/paypal/{order}', [PaymentController::class, 'redirectToPayPal'])
        ->name('payment.paypal.redirect');

    Route::get('/payment/omnipay/{order}', [OmnipayPaymentController::class, 'redirect'])
        ->name('payment.omnipay.redirect');
});

/*
|--------------------------------------------------------------------------
| 4) Public Frontend Products / Cart / Checkout / Wishlist
|--------------------------------------------------------------------------
*/
Route::get('/products', [FrontProductController::class, 'index'])
    ->name('frontend.products.index');

Route::get('/products/{slug}', [FrontProductController::class, 'show'])
    ->name('frontend.products.show');

Route::post('/products/{product}/reviews', [FrontReviewController::class, 'store'])
    ->name('frontend.products.reviews.store');

Route::get('/cart', [CartController::class, 'index'])
    ->name('frontend.cart.index');

Route::middleware('auth')->group(function () {
    Route::post('/cart/add/{product}', [CartController::class, 'add'])
        ->name('frontend.cart.add');

    Route::post('/cart/update/{product}', [CartController::class, 'update'])
        ->name('frontend.cart.update');

    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])
        ->name('frontend.cart.remove');

    Route::delete('/cart/clear', [CartController::class, 'clear'])
        ->name('frontend.cart.clear');
});

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('frontend.checkout.index');

    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])
        ->name('frontend.checkout.applyCoupon');

    Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])
        ->name('frontend.checkout.removeCoupon');

    Route::post('/checkout/select-address', [CheckoutController::class, 'selectAddress'])
        ->name('frontend.checkout.selectAddress');

    Route::post('/checkout/address', [CheckoutController::class, 'storeAddress'])
        ->name('frontend.checkout.storeAddress');

    Route::post('/checkout/select-shipping', [CheckoutController::class, 'selectShipping'])
        ->name('frontend.checkout.selectShipping');

    Route::post('/checkout/select-payment', [CheckoutController::class, 'selectPayment'])
        ->name('frontend.checkout.selectPayment');

    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])
        ->name('frontend.checkout.placeOrder');

    Route::get('/checkout/thank-you/{order}', [CheckoutController::class, 'thankYou'])
        ->name('frontend.checkout.thankYou');
});

Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])
        ->name('frontend.wishlist.index');

    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])
        ->name('frontend.wishlist.add');

    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])
        ->name('frontend.wishlist.remove');
});

/*
|--------------------------------------------------------------------------
| 5) Auth Routes
|--------------------------------------------------------------------------
*/
Auth::routes();

Route::get('/home', function () {
    return redirect()->route('frontend.home');
})->name('home.redirect');

/*
|--------------------------------------------------------------------------
| 6) Admin Auth + Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])
        ->middleware('guest:admin')
        ->name('login');

    Route::post('/login', [AdminLoginController::class, 'login'])
        ->middleware('guest:admin')
        ->name('login.submit');

    Route::post('/logout', [AdminLoginController::class, 'logout'])
        ->name('logout');

    Route::middleware(['auth:admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::get('/shippingway', [ShippingMethodController::class, 'index'])->name('shippingway.index');
        Route::get('/shippingway/create', [ShippingMethodController::class, 'create'])->name('shippingway.create');
        Route::post('/shippingway', [ShippingMethodController::class, 'store'])->name('shippingway.store');
        Route::get('/shippingway/{shippingway}/edit', [ShippingMethodController::class, 'edit'])->name('shippingway.edit');
        Route::put('/shippingway/{shippingway}', [ShippingMethodController::class, 'update'])->name('shippingway.update');
        Route::delete('/shippingway/{shippingway}', [ShippingMethodController::class, 'destroy'])->name('shippingway.destroy');

        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/tags', [AdminTagController::class, 'index'])->name('tags.index');
        Route::get('/tags/create', [AdminTagController::class, 'create'])->name('tags.create');
        Route::post('/tags', [AdminTagController::class, 'store'])->name('tags.store');
        Route::get('/tags/{tag}/edit', [AdminTagController::class, 'edit'])->name('tags.edit');
        Route::put('/tags/{tag}', [AdminTagController::class, 'update'])->name('tags.update');
        Route::delete('/tags/{tag}', [AdminTagController::class, 'destroy'])->name('tags.destroy');

        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/create', [AdminCouponController::class, 'create'])->name('coupons.create');
        Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/{coupon}', [AdminCouponController::class, 'show'])->name('coupons.show');
        Route::get('/coupons/{coupon}/edit', [AdminCouponController::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{coupon}', [AdminCouponController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
        Route::get('/reviews/{review}/edit', [AdminReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('/reviews/{review}', [AdminReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        Route::get('/supervisors', [SupervisorController::class, 'index'])->name('supervisors.index');
        Route::get('/supervisors/create', [SupervisorController::class, 'create'])->name('supervisors.create');
        Route::post('/supervisors', [SupervisorController::class, 'store'])->name('supervisors.store');
        Route::get('/supervisors/{supervisor}', [SupervisorController::class, 'show'])->name('supervisors.show');
        Route::get('/supervisors/{supervisor}/edit', [SupervisorController::class, 'edit'])->name('supervisors.edit');
        Route::put('/supervisors/{supervisor}', [SupervisorController::class, 'update'])->name('supervisors.update');

        Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
        Route::get('/countries/create', [CountryController::class, 'create'])->name('countries.create');
        Route::post('/countries', [CountryController::class, 'store'])->name('countries.store');
        Route::get('/countries/{country}/edit', [CountryController::class, 'edit'])->name('countries.edit');
        Route::put('/countries/{country}', [CountryController::class, 'update'])->name('countries.update');
        Route::get('/countries/{country}', [CountryController::class, 'show'])->name('countries.show');
        Route::delete('/countries/{country}', [CountryController::class, 'destroy'])->name('countries.destroy');

        Route::get('/states', [StateController::class, 'index'])->name('states.index');
        Route::get('/states/create', [StateController::class, 'create'])->name('states.create');
        Route::post('/states', [StateController::class, 'store'])->name('states.store');
        Route::get('/states/{state}/edit', [StateController::class, 'edit'])->name('states.edit');
        Route::put('/states/{state}', [StateController::class, 'update'])->name('states.update');
        Route::get('/states/{state}', [StateController::class, 'show'])->name('states.show');
        Route::delete('/states/{state}', [StateController::class, 'destroy'])->name('states.destroy');

        Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
        Route::get('/cities/create', [CityController::class, 'create'])->name('cities.create');
        Route::post('/cities', [CityController::class, 'store'])->name('cities.store');
        Route::get('/cities/{city}/edit', [CityController::class, 'edit'])->name('cities.edit');
        Route::put('/cities/{city}', [CityController::class, 'update'])->name('cities.update');
        Route::get('/cities/{city}', [CityController::class, 'show'])->name('cities.show');
        Route::delete('/cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');

        Route::get('/addresses', [UserAddressController::class, 'index'])->name('addresses.index');
        Route::get('/addresses/create', [UserAddressController::class, 'create'])->name('addresses.create');
        Route::post('/addresses', [UserAddressController::class, 'store'])->name('addresses.store');
        Route::get('/addresses/{address}/edit', [UserAddressController::class, 'edit'])->name('addresses.edit');
        Route::put('/addresses/{address}', [UserAddressController::class, 'update'])->name('addresses.update');
        Route::get('/addresses/{address}', [UserAddressController::class, 'show'])->name('addresses.show');
        Route::delete('/addresses/{address}', [UserAddressController::class, 'destroy'])->name('addresses.destroy');

        Route::get('/shipping_companies', [ShippingCompanyController::class, 'index'])->name('shipping_companies.index');
        Route::get('/shipping_companies/create', [ShippingCompanyController::class, 'create'])->name('shipping_companies.create');
        Route::post('/shipping_companies', [ShippingCompanyController::class, 'store'])->name('shipping_companies.store');
        Route::get('/shipping_companies/{shipping_company}/edit', [ShippingCompanyController::class, 'edit'])->name('shipping_companies.edit');
        Route::put('/shipping_companies/{shipping_company}', [ShippingCompanyController::class, 'update'])->name('shipping_companies.update');
        Route::get('/shipping_companies/{shipping_company}', [ShippingCompanyController::class, 'show'])->name('shipping_companies.show');
        Route::delete('/shipping_companies/{shipping_company}', [ShippingCompanyController::class, 'destroy'])->name('shipping_companies.destroy');

        Route::get('/account/settings', [AccountSettingsController::class, 'edit'])->name('account.edit');
        Route::put('/account/settings', [AccountSettingsController::class, 'update'])->name('account.update');
    });
});

/*
|--------------------------------------------------------------------------
| 7) Shared Backoffice Routes (Admin + Supervisor) - /dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin', 'role:admin|supervisor,admin'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

        Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    });

Route::middleware(['web'])->group(function () {

    Route::get('/my-notifications', [FrontNotificationController::class, 'index'])
        ->name('front.notifications.index');

    Route::get('/my-notifications/{id}/open', [FrontNotificationController::class, 'open'])
        ->name('front.notifications.open');

    Route::post('/my-notifications/read-all', [FrontNotificationController::class, 'markAllAsRead'])
        ->name('front.notifications.readAll');
});

/*
|--------------------------------------------------------------------------
| 8) Supervisor Routes (role: supervisor)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin', 'role:supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {

        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/orders', [AdminOrderController::class, 'index'])
            ->name('orders.index')
            ->middleware('permission:view orders');
    });

/*
|--------------------------------------------------------------------------
| 9) Customer Routes (role: customer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])
    ->prefix('account')
    ->name('customer.')
    ->group(function () {



        // ✅✅ INVOICES ROUTES (مكانها الصح هنا)
        Route::get('/invoices/{invoice}', [CustomerInvoiceController::class, 'view'])
            ->name('invoices.view');

        Route::get('/invoices/{invoice}/download', [CustomerInvoiceController::class, 'download'])
            ->name('invoices.download');

        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/orders', [CustomerOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])
            ->name('orders.cancel');

        Route::get('/profile', [CustomerDashboardController::class, 'profile'])
            ->name('profile');

        Route::put('/profile', [CustomerDashboardController::class, 'updateProfile'])
            ->name('profile.update');

        Route::get('/addresses', [AddressController::class, 'index'])
            ->name('addresses.index');

        Route::get('/addresses/create', [AddressController::class, 'create'])
            ->name('addresses.create');

        Route::post('/addresses', [AddressController::class, 'store'])
            ->name('addresses.store');

        Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])
            ->name('addresses.edit');

        Route::put('/addresses/{address}', [AddressController::class, 'update'])
            ->name('addresses.update');

        Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])
            ->name('addresses.destroy');
    });

/*
|--------------------------------------------------------------------------
| 10) Test / Debug Route
|--------------------------------------------------------------------------
*/
Route::get('/test-role', function () {
    if (!auth()->check()) {
        return 'Not logged in';
    }

    return [
        'user'  => auth()->user()->email,
        'roles' => auth()->user()->getRoleNames(),
        'perms' => auth()->user()->getAllPermissions()->pluck('name'),
    ];
})->middleware('auth');

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');
