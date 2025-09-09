<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('customer-phone.index');
});

Route::get('/api/customer-phone', [CustomerController::class, 'index']);
