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
    Route::apiResource('users', 'UserController');

    Route::post('roles/{role_id}/attach_users', 'RoleController@attachUser');
    Route::apiResource('roles', 'RoleController');
    Route::get('permissions', 'RoleController@getAbilities');
    
    Route::apiResource('category', 'CategoryController');

    Route::post('projects/{id}/favourite', 'ProjectController@favourite_project');
    Route::apiResource('projects', 'ProjectController');
    
    Route::post('lists/sorting', 'TaskListController@list_sorting' );
    Route::get('lists/search', 'TaskListController@list_search' );
    Route::put('lists/{id}/privacy', 'TaskListController@privacy' );
    Route::put('lists/{id}/detach-users', 'TaskListController@detach_users' );
    Route::put('lists/{id}/attach-users', 'TaskListController@attach_users' );
    Route::apiResource('lists', 'TaskListController');
    
    
    
    Route::post('tasks/reorder', 'TaskController@reorder');
    Route::post('tasks/sorting', 'TaskController@task_sorting');
    Route::put('tasks/{id}/attach-users', 'TaskController@attach_users');
    Route::put('tasks/{id}/detach-users', 'TaskController@detach_users');
    Route::put('tasks/{id}/boards', 'TaskController@attach_to_board');
    Route::delete('tasks/{id}/boards', 'TaskController@detach_from_board');
    Route::get('tasks/{id}/activity', 'TaskController@activities');
    Route::put('tasks/{id}/privacy', 'TaskController@privacy');
    Route::put('tasks/{id}/change-status', 'TaskController@change_status');
    Route::apiResource('tasks', 'TaskController');
   
    Route::put('discussions/{id}/attach-users', 'TaskController@attach_users');
    Route::put('discussions/{id}/detach-users', 'TaskController@detach_users');
    Route::put('discussions/{id}/privacy', 'TaskController@privacy');
    Route::apiResource('discussions', 'DiscussionController');
    Route::apiResource('milestones', 'MilestoneController');
    
    Route::apiResource('comments', 'CommentController');

    Route::get('activities', 'ActivityController@index');
    
});