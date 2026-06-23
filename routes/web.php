<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Added for the logout route

// Livewire Components
use App\Livewire\Shop\Index;
use App\Livewire\Shop\NewArrival;
use App\Livewire\Shop\Occasion;
use App\Livewire\Shop\About;
use App\Livewire\Shop\Cart; 
use App\Livewire\Shop\Wishlist;
use App\Livewire\Auth\CustomerLogin;
use App\Livewire\Shop\Product as ProductComponent; 

// Database Models
use App\Models\Carousel;
use App\Models\Product as ProductModel; 

// --- AUTH REDIRECT ---
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');

// --- HOME PAGE (Combined into a single route) ---
Route::get('/', function () {
    return view('home', [
        // Fetches active carousels
        'carousels' => Carousel::where('is_active', true)->orderBy('sort_order', 'asc')->get(),
        
        // Fetches up to 4 sarees marked as "Best Seller"
        'bestSellers' => ProductModel::where('is_best_seller', true)->latest()->take(4)->get(),
        
        // Fetches up to 4 sarees marked as "New Arrival"
        'latestCollection' => ProductModel::where('is_new', true)->latest()->take(4)->get(),
    ]);
})->name('home');

// --- SHOP PAGES ---
Route::get('/all-sarees', Index::class)->name('shop.index');
Route::get('/new-arrival', NewArrival::class)->name('shop.new-arrival');
Route::get('/occasion', Occasion::class)->name('shop.occasion');
Route::get('/about', About::class)->name('shop.about');

// --- SEARCH SUGGESTIONS ---
Route::get('/api/search-suggestions', function (Illuminate\Http\Request $request) {
    $search = $request->query('q');
    if (!$search || strlen($search) < 2) return response()->json([]);
    
    $suggestions = \App\Models\Product::where('name', 'like', "%{$search}%")
        ->select('name')
        ->limit(8)
        ->get()
        ->pluck('name');
        
    return response()->json($suggestions);
})->name('api.search.suggestions');

// --- CART PAGE ---
Route::get('/cart', Cart::class)->name('cart');

Route::get('/password/reset/{token}', \App\Livewire\Auth\ResetPassword::class)->name('password.reset'); 

// --- WISHLIST PAGE ---
Route::get('/wishlist', Wishlist::class)->name('wishlist');

// --- SINGLE PRODUCT PAGE ---
Route::get('/product/{slugOrId}', ProductComponent::class)->name('shop.product');

// --- PROFILE PAGES ---
Route::middleware('auth:customer')->group(function () {
    Route::get('/profile', App\Livewire\Profile\AccountDetails::class)->name('profile.account');
    Route::get('/profile/orders', App\Livewire\Profile\OrderHistory::class)->name('profile.orders');
    Route::get('/profile/orders/{order}/invoice', [App\Http\Controllers\InvoiceController::class, 'download'])->name('profile.orders.invoice');
    Route::get('/profile/orders/{id}', App\Livewire\Profile\OrderDetails::class)->name('profile.orders.details');
    // Backward compatibility for old track URLs
    Route::get('/profile/orders/{id}/track', function ($id) {
        return redirect()->route('profile.orders.details', ['id' => $id]);
    })->name('profile.orders.track');
    Route::get('/profile/addresses', App\Livewire\Profile\Addresses::class)->name('profile.addresses');
});

// --- CUSTOMER LOGOUT ROUTE ---
Route::post('/customer/logout', function () {
    Auth::guard('customer')->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('home');
})->name('customer.logout');

// --- POLICY PAGES ---
Route::get('/privacy-policy', function () {
    return view('pages.privacy', ['policy' => \App\Models\PolicyPage::firstOrCreate(['id' => 1])]);
})->name('policy.privacy');

Route::get('/terms-and-conditions', function () {
    return view('pages.terms', ['policy' => \App\Models\PolicyPage::firstOrCreate(['id' => 1])]);
})->name('policy.terms');

Route::get('/shipping-policy', function () {
    return view('pages.shipping', ['policy' => \App\Models\PolicyPage::firstOrCreate(['id' => 1])]);
})->name('policy.shipping');

Route::get('/refund-policy', function () {
    return view('pages.refund', ['policy' => \App\Models\PolicyPage::firstOrCreate(['id' => 1])]);
})->name('policy.refund');

Route::get('/faqs', function () {
    return view('pages.faqs', ['policy' => \App\Models\PolicyPage::firstOrCreate(['id' => 1])]);
})->name('policy.faqs');

// Add these inside the auth:customer middleware group in routes/web.php
Route::get('/checkout/address', App\Livewire\Checkout\Address::class)->name('checkout.address');
Route::get('/checkout/summary', App\Livewire\Checkout\Summary::class)->name('checkout.summary');
Route::get('/checkout/success/{orderId}', App\Livewire\Checkout\Success::class)->name('checkout.success');