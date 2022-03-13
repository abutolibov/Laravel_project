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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

Auth::routes();


Route::get('/manager', [App\Http\Controllers\HomeController::class, 'manager'])->name('manager')->middleware('role:manager', 'auth');
Route::post('/message', [App\Http\Controllers\HomeController::class, 'message_store'])->name('message')->middleware('auth');
Route::get('/change-status/{id}', [App\Http\Controllers\HomeController::class, 'change_status'])->name('change_status')->middleware('role:manager','auth');
