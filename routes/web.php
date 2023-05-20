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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'admin'])->name('admin');

Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login.post');Route::post('/admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');//Admin Home page after login
Route::group(['middleware'=>'admin'], function() {
    Route::get('/admin/home', 'Admin\HomeController@index');
});
