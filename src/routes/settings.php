<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Settings Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "settings" middleware group. Now create something great!
|
*/

Route::resource('users', 'UserController', ['except' => ['show', 'create', 'store', 'edit', 'update', 'destroy']]);
Route::resource('sessions', 'SessionController', ['except' => ['show', 'create', 'store', 'edit', 'update', 'destroy']]);
