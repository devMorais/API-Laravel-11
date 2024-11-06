<?php

use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//ROTAS API PRODUTOS
Route::group(['prefix' => 'v1'], function () {
    Route::get('/products/search', [ProductController::class, 'search']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);
});
