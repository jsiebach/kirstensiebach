<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', ['uses' => 'PageController@home']);

Route::get('{slug}', ['uses' => 'PageController@view'])
    ->where(['slug' => '^((?!(admin|nova-api|nova-vendor)).)*$'])
    ->name('page.show');
