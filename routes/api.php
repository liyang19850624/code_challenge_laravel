<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use Api\Controllers\ProductController as ProductApiController;
use Api\Controllers\ProductsController as ProductsApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ProductApiController::class)->group(function() {
    Route::get('/product/{id}', 'get');
    Route::patch('/product/{id}', 'update');
    Route::delete('/product/{id}', 'delete');
});

Route::controller(ProductsApiController::class)->group(function() {
    Route::get('/products', 'list');
    Route::post('/products', 'new');
});