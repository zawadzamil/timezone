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

Route::get('/', function () {
    return view('home');
});

Route::get('watches', function () {
    return view('watches');
});

Route::get('registerAdmin', function () {
    return view('auth.registerAdmin');
});

Route::post('adminRegister','App\Http\Controllers\DashboardController@registerAdmin');


Route::get('logout','App\Http\Controllers\DashboardController@logout');



Route::post('uploadImage','App\Http\Controllers\MediaController@media');


Route::get('auth/google', 'App\Http\Controllers\Auth\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'App\Http\Controllers\Auth\GoogleController@handleGoogleCallback');

Route::get('/dashboard', function () {
    return view('home');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
