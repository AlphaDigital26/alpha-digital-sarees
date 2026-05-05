<?php

use App\Livewire\Shop\Index;

Route::get('/shop', Index::class);

Route::get('/', function () {
    return view('home');
});