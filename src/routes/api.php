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

// workload
Route::group(
    [
        'prefix' => 'workload',
        'middleware' => ['jwt.auth'],
    ],
    function () {
        // get workload by workload record ID.
        Route::get('get/id/{id}', 'WorkloadController@getWorkloadById');

        // get workload by user ID.
        Route::get('get/user_id/{id}', 'WorkloadController@getWorkloadByUserId');

        // get workload by user Id and month
        Route::get(
            'get/user/id/{id}/month/{month}',
            'WorkloadController@getWorkloadByMonth'
        )
        ->name('getWorkloadByMonth');

        // get workload by user Id and weeks
        Route::get(
            'get/user/id/{id}/week/{week}',
            'WorkloadController@getWorkloadByWeeks'
        )
        ->name('getWorkloadByWeek');

        // set workload to data store.
        Route::post('set/user_id', 'WorkloadController@setWorkloadByUserId');

        // update workload (several data)
        Route::post('update/user_id', 'WorkloadController@updateWorkloadByUserId')
        ->name('updateWorkloadByUserId');
    }
);

// project
Route::group(
    [
        'prefix' => 'project',
        'middleware' => ['jwt.auth'],
    ],
    function () {
        Route::get('/get', 'ProjectController@getProjectAndCategoryList')
        ->name('getProjectAndCategory');
    }
);

// report comment
Route::group(
    [
        'prefix' => 'report_comment',
        'middleware' => ['jwt.auth'],
    ],
    function () {
        Route::get('/get/{id}', 'ReportCommentController@getReportCommentById')
        ->name('getReportCommentById');

        Route::get('/get/user/{id}/week/{week}', 'ReportCommentController@getReportCommentByUserId')
        ->name('getReportCommentByUserId');

        Route::post('/save', 'ReportCommentController@createOrUpdateReportComment')
        ->name('saveReportComment');
    }
);

Route::group(
    ['prefix' => 'auth'],
    function () {
        // register
        Route::post('/register', 'Auth\AuthController@register')->name('register');

        // authenticate
        Route::post('/authenticate', 'Auth\AuthController@authenticate')->name('authenticate');

        // confirm
        Route::post('/confirm', 'Auth\AuthController@confirm')->name('confirm');

        // me (get my data)
        Route::get('/me', 'Auth\AuthController@getMyData')->name('getMyData');
    }
);
