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

// 案件
Route::group(['prefix' => 'workload'], function () {
    Route::get('get/id/{id}', 'WorkloadController@getWorkloadById');
    Route::get('get/user_id/{id}', 'WorkloadController@getWorkloadByUserId');

    Route::post('set/user_id', 'WorkloadController@setWorkloadByUserId');
});
