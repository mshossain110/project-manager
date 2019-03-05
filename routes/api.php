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
    
    Route::post('lists/sorting', 'TaskListController@list_sorting' );
    Route::get('lists/search', 'TaskListController@list_search' );
    Route::put('lists/{id}/privacy', 'TaskListController@privacy' );
    Route::put('lists/{id}/detach-users', 'TaskListController@detach_users' );
    Route::put('lists/{id}/attach-users', 'TaskListController@attach_users' );
    Route::resource('lists', 'TaskListController', ['except' => ['edit']]);
    
    
    
    Route::post('tasks/reorder', 'TaskController@reorder');
    Route::post('tasks/sorting', 'TaskController@task_sorting');
    Route::put('tasks/{id}/attach-users', 'TaskController@attach_users');
    Route::put('tasks/{id}/detach-users', 'TaskController@detach_users');
    Route::put('tasks/{id}/boards', 'TaskController@attach_to_board');
    Route::delete('tasks/{id}/boards', 'TaskController@detach_from_board');
    Route::get('tasks/{id}/activity', 'TaskController@activities');
    Route::put('tasks/{id}/privacy', 'TaskController@privacy');
    Route::put('tasks/{id}/change-status', 'TaskController@change_status');
    Route::resource('tasks', 'TaskController', ['except' => ['edit']]);

});