<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('users', [UserController::class, 'store']);

Route::post('products', [ProductController::class, 'store']);
Route::put('products/{product}', [ProductController::class, 'update']); // Actualizar producto
Route::delete('products/{product}', [ProductController::class, 'destroy']); // Eliminar producto

Route::post('users/{user}/attach-product', [UserController::class, 'attachProduct']); // Asignar producto
Route::post('users/{user}/detach-product', [UserController::class, 'detachProduct']); // Desasignar producto
