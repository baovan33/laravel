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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



route::middleware('auth')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'show']);
    Route::get('admin', [App\Http\Controllers\DashboardController::class, 'show']);
    Route::get('admin/user/list', [App\Http\Controllers\AdminUserController::class, 'list'])->name('user.list');
    route::get('admin/user/add', [App\Http\Controllers\AdminUserController::class, 'add']);
    route::post('admin/user/store', [App\Http\Controllers\AdminUserController::class, 'store']);
    route::get('admin/user/delete/{id}', [App\Http\Controllers\AdminUserController::class, 'delete'])->name('user.delete');
    route::get('admin/user/action', [App\Http\Controllers\AdminUserController::class, 'action']);
    route::get('admin/user/edit/{id}', [App\Http\Controllers\AdminUserController::class, 'edit'])->name('user.edit');
    route::post('admin/user/update/{id}', [App\Http\Controllers\AdminUserController::class, 'update'])->name('user.update');

    #product
    route::get('admin/product/add', [App\Http\Controllers\AdminProductController::class, 'add']);
    route::post('admin/product/store', [App\Http\Controllers\AdminProductController::class, 'store'])->name('product.store');
    route::get('admin/product/list', [App\Http\Controllers\AdminProductController::class, 'list'])->name('product.list');
    route::get('admin/product/delete/{id}', [App\Http\Controllers\AdminProductController::class, 'delete'])->name('product.delete');
    route::get('admin/product/action', [App\Http\Controllers\AdminProductController::class, 'action'])->name('product.action');
    route::get('admin/product/edit/{id}', [App\Http\Controllers\AdminProductController::class, 'edit'])->name('product.edit');
    route::post('admin/product/update/{id}', [App\Http\Controllers\AdminProductController::class, 'update'])->name('product.update');
    route::get('admin/product/cat/list', [App\Http\Controllers\AdminProductController::class, 'cat_list'])->name('product.cat_list');











});

