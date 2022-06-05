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
Route::get('/', fn() => redirect('/home'));

Route::controller(HomeController::class)->group(function() {
    Route::get('/home', 'index');
    Route::get('/about-us', 'aboutUs');
});

Route::controller(ProductController::class)->group(function() {
    Route::get('/products', 'index');
    Route::get('/product/new', 'create');
    Route::get('/product/{id}/edit', 'edit');
});

