<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Public Routes (No Authentication Required)
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/debug-admin', function () {
    $admin = \App\Models\User::where('email', 'admin@tokoecommerce.com')->first();
    if (!$admin) {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'UserSeeder']);
        return "Admin user seeded!";
    }
    return "Admin user exists: " . $admin->name;
});

// Public Product Routes (accessible to everyone)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/featured', [ProductController::class, 'featured'])->name('products.featured');
Route::get('/products/new-arrivals', [ProductController::class, 'newArrivals'])->name('products.new-arrivals');
Route::get('/products/category/{category}', [ProductController::class, 'byCategory'])->name('products.category');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search/products', [ProductController::class, 'search'])->name('products.search');

// Auth Routes (from Breeze)
require __DIR__.'/auth.php';

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
    Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');
    
    // Checkout Routes
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    
    // Variant selection (requires auth)
    Route::post('/products/{product}/variant', [ProductController::class, 'getVariant'])->name('products.variant');
    
    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Review Routes
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        
        Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show']);
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('orders/{order}/payment-status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
        Route::patch('orders/{order}/shipping-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateShippingStatus'])->name('orders.update-shipping-status');

        Route::get('reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::patch('reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
        Route::delete('reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        
        Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

        Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    });

    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'store'])->name('wishlist.add');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

// Profile Routes (from Breeze - keep these)
Route::middleware('auth')->group(function () {
    // Location helpers (used by profile address form)
    Route::get('/locations/cities', [\App\Http\Controllers\LocationController::class, 'cities'])->name('locations.cities');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Address management in profile
    Route::post('/profile/address', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::patch('/profile/address/{address}/primary', [ProfileController::class, 'setPrimaryAddress'])->name('profile.address.primary');
    Route::delete('/profile/address/{address}', [ProfileController::class, 'deleteAddress'])->name('profile.address.destroy');
});