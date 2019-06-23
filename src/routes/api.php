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

// workload
Route::group(['prefix' => 'workload'], function () {
    // get workload
    Route::get('get/id/{id}', 'WorkloadController@getWorkloadById');
    Route::get('get/user_id/{id}', 'WorkloadController@getWorkloadByUserId');

    // set workload to data store.
    Route::post('set/user_id', 'WorkloadController@setWorkloadByUserId');
});


Route::group(['prefix' => 'auth'], function () {
    // register
    Route::post('/register', 'Auth\AuthController@register')->name('register');

    // authenticate
    Route::post('/authenticate', 'Auth\AuthController@authenticate')->name('authenticate');
});
