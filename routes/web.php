<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [RegisterController::class, 'register']);

// Route::get('/home', [ProductController::class, 'index'])->name('home');
// Route::get('/', [ProductController::class, 'index'])->name('home');

// Route::get('/cart/select', [CartController::class, 'showProductSelector'])->name('cart.select');
// Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
// Route::get('/cart/view', [CartController::class, 'index'])->name('cart.view');
// Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

// Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
// Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Route::middleware(['web'])->group(function () {
//     Route::get('/', [ProductController::class, 'index'])->name('home');
// });
// Route::get('/force-login', function () {
//     $admin = User::where('role', 'admin')->first();
//     Auth::login($admin);
//     return redirect()->route('home');
// });

// Route::get('/admin/orders', [ProductController::class, 'viewOrders'])->name('admin.orders');
// Route::get('/admin/product-report', [ProductController::class, 'productReport'])->name('admin.productReport');

// // Admin product management
// Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
// Route::post('/products', [ProductController::class, 'store'])->name('products.store');
// Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
// Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
// Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');



// // use Illuminate\Support\Facades\Route;

// // Route::get('/', function () {
// //     return view('welcome');
// // });
Route::middleware(['web'])->group(function () {

    // Auth
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Home/Product
    Route::get('/', [ProductController::class, 'index'])->name('home');
    Route::get('/home', [ProductController::class, 'index']);

    // Cart
    Route::get('/cart/select', [CartController::class, 'showProductSelector'])->name('cart.select');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart/view', [CartController::class, 'index'])->name('cart.view');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

    // Checkout
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // Admin panel
    Route::get('/admin/orders', [ProductController::class, 'viewOrders'])->name('admin.orders');
    Route::get('/admin/product-report', [ProductController::class, 'productReport'])->name('admin.productReport');

    // Admin product management
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Force login (for testing)
    Route::get('/force-login', function () {
        $admin = User::where('role', 'admin')->first();
        Auth::login($admin);
        return redirect()->route('home');
    });
});