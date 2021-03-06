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
Route::group(['middleware' => ['auth:api']], function () {
    Route::apiResources(['user'=>'API\UserController']);
    Route::apiResources(['hotelpackage'=>'API\HotelPackagesController']);
    Route::get('getpackageslist','API\HotelPackagesController@getAll');
    Route::get('profile','API\UserController@profile');
    Route::put('profile','API\UserController@updateProfile');
});
