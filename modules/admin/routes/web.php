<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
  $module = [
    'module_env' => module_env(),
    'module_config' => module_config()
  ];
  Route::get('/register', 'Modules\\' . str_replace('-', '_', module_env('NAME'))  . '\App\Http\Controllers\Controller@view_register');
  Route::get('/login', 'Modules\\' . str_replace('-', '_', module_env('NAME'))  . '\App\Http\Controllers\Controller@view_login');
  Route::get('/forget-password', 'Modules\\' . str_replace('-', '_', module_env('NAME'))  . '\App\Http\Controllers\Controller@view_forget_password');
  Route::get('/', 'Modules\\' . str_replace('-', '_', module_env('NAME'))  . '\App\Http\Controllers\Controller@view_index');

  Route::get('/config', 'Modules\\' . str_replace('-', '_', module_env('NAME'))  . '\App\Http\Controllers\Controller@view_config');
  Route::prefix('market')->group(function () use ($module) {
    Route::get('/', function (Request $request) use ($module) {
      return view("admin.market.index", $module);
    });
  });
});
