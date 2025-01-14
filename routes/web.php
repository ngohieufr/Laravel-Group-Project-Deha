<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->name('users.')->prefix('/users')->controller(UserController::class)->group(function(){
    Route::get('/', 'index')->middleware('check.user:view')->name('index');
    Route::get('/create', 'create')->middleware('check.user:create')->name('create');
    Route::post('/store', 'store')->middleware('check.user:create')->name('store');
    Route::get('/{id}/edit', 'edit')->middleware('check.user:edit')->name('edit');
    Route::put('/{id}/update', 'update')->middleware('check.user:edit')->name('update');
    Route::delete('/{id}/destroy', 'destroy')->middleware('check.user:delete')->name('destroy');
    Route::get('/{id}/view', 'show')->name('show');
});

Route::middleware(['auth'])->name('roles.')->prefix('/roles')->controller(RoleController::class)->group(function(){
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/{id}/edit', 'edit')->name('edit');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/destroy', 'destroy')->name('destroy');
});
