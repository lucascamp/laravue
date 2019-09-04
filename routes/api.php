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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 */
//Route::resource('/users', 'UsersController');
Route::get('users', 'UsersController@index');
Route::post('users/store', 'UsersController@store');
Route::put('users/update/{id}', 'UsersController@update');
Route::delete('users/delete/{id}', 'UsersController@destroy');


Route::post('teste', 'PayzenController@store');
Route::post('teste/store', 'PayzenController@store1')->name('xuxa');

//Route::apiResource('users', Api\UsersController::class);