<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('create_booking', 'HomeController@postCreateBookings');
Route::post('cancel_booking', 'HomeController@postCancelBookings');
Route::post('all_booking', 'HomeController@getBookings');
Route::post('accept_booking', 'HomeController@postAcceptBookings');

