<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductReviewController;





// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/layout', function () {
    return view('dashboard.layout-static');
});
// Route::get('/profile', function () {
//     return view('profiles.list');
// });

Route::get('/list', function () {
    return view('user_products.list2');
});
Route::get('/text', function () {
    return view('carts.text');
});

Route::get('/test', function () {
    return view('carts.test');
});
Route::get('/layout-index', function () {
    return view('dashboard.index');
});
Route::get('/layout-sidenav', function () {
    return view('dashboard.layout-sidenav-light');
});
Route::get('/tables', function () {
    return view('dashboard.tables');
});
Route::get('/admins', function () {
    return view('layouts.admin');
});
// Route::get('/info', function () {
//     $info = [
//         'name' => 'Y THANH ÊBAN',
//         'profile' => 'full stack developer.',
//         'email' => 'ebanythanh1@gmail.com.',
//         'phone' => '0337937493.',
//     ];
//     return view('info', compact('info'));
// });
Route::get('/index', function () {
    $data = [
        'name' => 'Y THANH ÊBAN2323423423423423234234234234',
        'profile' => 'full stack developer.',
        'email' => 'ebanythanh1@gmail.com.',
        'phone' => '0337937493.',
        '1' => '',
        '2' => '',
        '3' => '',
        '4' => '',
        '5' => '',
    ];
    return view('index', compact('data'));
});

Route::get('/info/{id}', function (string $id) {
    $data = array(
        '1' => array(
            'id' => 1,
            'name' => 'Y THANH ÊBAN',
            'profile' => 'Full Stack Developer',
            'email' => 'ebanythanh1@gmail.com',
            'phone' => '0337937493',
        ),
        '2' => array(
            'id' => 2,
            'name' => 'Nam Nguyễn',
            'profile' => 'Backend Developer',
            'email' => 'namnguyen@example.com',
            'phone' => '0123456789',
        ),
        '3' => array(
            'id' => 3,
            'name' => 'Linh Trần',
            'profile' => 'Frontend Developer',
            'email' => 'linhtran@example.com',
            'phone' => '0987654321',
        )
    );

    if (!empty($data[$id])) {
        $info = $data[$id];
        return view('info', compact('info'));
    } else {
        return view('404');
    }
});

//group routes
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('/products', [ProductController::class, 'index'])->name('products.list');
    Route::get('/create', [ProductController::class, 'create']);
    Route::post('/products/store', [ProductController::class, 'store']);
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/edit/{product}', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/update/{product}', [ProductController::class, 'update']);
    Route::delete('/products/delete/{product}', [ProductController::class, 'destroy']);


    // categories controller
    Route::resource('categories', CategoryController::class);
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.list');
    Route::get('/categories_index', [CategoryController::class, 'index_2']);
    Route::get('/categories/create', [CategoryController::class, 'create']);
    Route::post('/categories/store', [CategoryController::class, 'store']);
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/edit/{category}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/update/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/delete/{category}', [CategoryController::class, 'delete']);


    // brands controller
    Route::resource('brands', BrandController::class);
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.list');
    Route::get('/brands/create', [BrandController::class, 'create']);
    Route::post('/brands/store', [BrandController::class, 'store']);
    Route::get('/brands/{brand}', [BrandController::class, 'show'])->name('brands.show');
    Route::get('/brands/edit/{brand}', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('/brands/update/{brand}', [BrandController::class, 'update']);
    Route::delete('/brands/delete/{brand}', [BrandController::class, 'destroy']);

    Route::resource('users', UserController::class);
    Route::get('/users', [UserController::class, 'index'])->name('users.list');
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users/store', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/users/update/{user}', [UserController::class, 'update']);
    Route::delete('/users/delete/{id}', [UserController::class, 'delete']);

    Route::resource('coupons', CouponController::class);
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.list');
    Route::get('/coupons_index', [CouponController::class, 'index_2']);
    Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons/store', [CouponController::class, 'store'])->name('coupons.store');
    // Route::get('/coupons/{id}', [CouponController::class,'show'])->name('coupons.show');
    Route::get('/coupons/edit/{coupon}', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/update/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/delete/{coupon}', [CouponController::class, 'delete']);


    // routes/web.php
    Route::get('admin/orders', [OrderController::class, 'list'])->name('admin.orders.index');
    Route::get('admin/orders/{id}', [OrderController::class, 'show_admin'])->name('show_admin.orders.show');
    Route::post('/orders/update/{id}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/products/{product}/review', [ProductReviewController::class, 'store'])->name('products.review.store')->middleware('auth');

    Route::get('/admin/revenue', [OrderController::class, 'revenue'])->name('admin.revenue');
});


// User Product
Route::get('/', [ProductController::class, 'user_index'])->name('user_products.list');
Route::get('/user_products', [ProductController::class, 'user_products']);
Route::get('/products/detail/{product}', [ProductController::class, 'product_detail']);



//




Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('/logout', [AuthController::class, 'logout']);

//


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);

Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);


//
Route::middleware(['require_login'])->group(function () {
    Route::get('/carts', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carts/add', [CartController::class, 'store'])->name('cart.store');
    Route::post('/carts/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::put('/carts/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/carts/delete/{id}', [CartController::class, 'destroy'])->name('cart.remove');
    Route::delete('/carts/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/change-password', [ChangePasswordController::class, 'showForm'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password.update');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');


    Route::post('/vnpay_payment', [PaymentController::class, 'vnpay_payment'])->name('vnpay.payment');
    Route::get('vnpay/return', [CartController::class, 'vnpayReturn'])->name('vnpay.return');
    Route::get('/checkout/momo-return', [CartController::class, 'momoReturn'])->name('momo.return');
    Route::get('zalopay/return', [CartController::class, 'zalopayReturn'])->name('zalopay.return');


    // Route::post('/calculate-shipping-fee', [AddressController::class, 'calculateShippingFee']);
    Route::get('/shipping/fee', [AddressController::class, 'calculateShippingFee2'])->name('calculate.shipping');

    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::get('/addresses/{id}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{id}', [AddressController::class, 'update'])->name('addresses.update');
});

Route::get('/thanks', function () {
    return view('carts.thanks');
})->name('carts.thanks');
Route::get('/address/province', [AddressController::class, 'getProvice'])->name('address.province');
Route::get('/address/district/{id}', [AddressController::class, 'getDistrict'])->name('address.district');
Route::get('/address/ward/{id}', [AddressController::class, 'getWard'])->name('address.ward');

Route::get('/address/provinces', [AddressController::class, 'getProvice'])->name('address.provinces');
Route::get('/address/districts/{provinceId}', [AddressController::class, 'getDistrict'])->name('address.districts');
Route::get('/address/wards/{districtId}', [AddressController::class, 'getWard'])->name('address.wards');
// nếu bạn muốn có nút "xóa toàn bộ"
