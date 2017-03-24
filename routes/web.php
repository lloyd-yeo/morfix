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

//Route::get('/', function () {
//    return view('index');
//});
//
//Route::get('/frontend_pricing.php', function() {
//    return view('frontend_pricing');
//});
//
//Route::get('/bd_dashboard', function() {
//    return view('bd_dashboard');
//});

Auth::routes();
Route::get('/', function(){ return view("index"); });
Route::get('/home', 'HomeController@index');
Route::get('/interactions', 'InteractionsController@index');
Route::get('/interactions/{id}', 'InteractionsController@edit');
Route::post('/interactions/like/{id}', 'InteractionsController@toggleLike');
Route::post('/interactions/comment/{id}', 'InteractionsController@toggleComment');
Route::post('/interactions/follow/{id}', 'InteractionsController@toggleFollow');
Route::post('/interactions/unfollow/{id}', 'InteractionsController@toggleUnfollow');
Route::post('/interactions/niche/{id}', 'InteractionsController@toggleNiche');
Route::post('/instagram-profile/add', 'InstagramProfileController@store');
Route::post('/dm', 'DirectMessageController@index');
Route::post('/dm/templates/{id}', 'DirectMessageTemplatesController@index');
Route::post('/dm/logs/{id}', 'DirectMessageLogsController@index');
