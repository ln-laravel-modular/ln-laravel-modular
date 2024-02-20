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

Route::prefix(module_env('ROUTE_PREFIX'))->group(function () {
  Route::get('/', 'Modules\\' . str_replace('-', '_', module_env('NAME'))  . '\App\Http\Controllers\Controller@view_index');
});