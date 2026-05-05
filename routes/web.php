<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Shop\Index;
use App\Livewire\Shop\NewArrival;
use App\Livewire\Shop\Occasion;
use App\Livewire\Shop\About;
use App\Livewire\Shop\Product; // Add this line

//home page
Route::get('/', function () {
    return view('home'); 
})->name('home');

//all sarees
Route::get('/all-sarees', Index::class)->name('shop.index');
//new arrival
Route::get('/new-arrival', NewArrival::class)->name('shop.new-arrival');
//occasion
Route::get('/occasion', Occasion::class)->name('shop.occasion');
//about
Route::get('/about', About::class)->name('shop.about');

// Product Route
Route::get('/product', Product::class)->name('shop.product');