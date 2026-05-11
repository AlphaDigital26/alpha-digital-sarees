<?php

use Illuminate\Support\Facades\Route;

// Livewire Components
use App\Livewire\Shop\Index;
use App\Livewire\Shop\NewArrival;
use App\Livewire\Shop\Occasion;
use App\Livewire\Shop\About;
use App\Livewire\Shop\Cart; // <-- Added Cart Component Import
use App\Livewire\Shop\Product as ProductComponent; 

// Database Models
use App\Models\Carousel;
use App\Models\Product as ProductModel; 

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

// --- CART PAGE ---
// <-- Added the named Cart Route
Route::get('/cart', Cart::class)->name('cart'); 

// --- SINGLE PRODUCT PAGE ---
Route::get('/product/{id}', ProductComponent::class)->name('shop.product');