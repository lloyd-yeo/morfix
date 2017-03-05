<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/frontend_pricing.php', function() {
    return view('frontend_pricing');
});

Route::get('/bd_dashboard.php', function() {
    return view('bd_dashboard');
});
