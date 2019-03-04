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

Route::group([
    'middleware' => ['auth:api'],
    'namespace' => 'Api\V1',
], function () {
    Route::delete('users/delete-multiple', 'UserController@deleteMultiple');
    Route::get('users/search', 'UserController@search');
    Route::resource('users', 'UserController', ['except' => ['edit']]);

    Route::post('roles/{role_id}/attach_users', 'RoleController@attachUser');
    Route::resource('roles', 'RoleController', ['except' => ['edit']]);
    Route::get('permissions', 'RoleController@getAbilities');
    
    Route::resource('category', 'CategoryController', ['except' => ['edit']]);

    Route::post('projects/{id}/favourite', 'ProjectController@favourite_project');
    Route::resource('projects', 'ProjectController', ['except' => ['edit']]);
    
    Route::put('lists/{id}/privacy', 'TaskListController@privacy' );
    Route::put('lists/{id}/detach-users', 'TaskListController@detach_users' );
    Route::put('lists/{id}/attach-users', 'TaskListController@attach_users' );
    Route::put('lists/sorting', 'TaskListController@list_sorting' );
    Route::get('lists/search', 'TaskListController@list_search' );
    Route::resource('lists', 'TaskListController', ['except' => ['edit']]);
    
});