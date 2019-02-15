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

});

