<?php

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

Route::post('register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('logout', [App\Http\Controllers\API\AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('katalog', [App\Http\Controllers\API\KatalogController::class, 'index']);
Route::get('katalog/create', [App\Http\Controllers\API\KatalogController::class, 'create'])->middleware('auth:sanctum');
Route::post('katalog/store', [App\Http\Controllers\API\KatalogController::class, 'store'])->middleware('auth:sanctum');
Route::get('katalog/edit/{id}', [App\Http\Controllers\API\KatalogController::class, 'edit'])->middleware('auth:sanctum');
Route::post('katalog/update/{id}', [App\Http\Controllers\API\KatalogController::class, 'update'])->middleware('auth:sanctum');
Route::post('katalog/delete/{id}', [App\Http\Controllers\API\KatalogController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('lihat-jasa/{id}', [App\Http\Controllers\API\KatalogController::class, 'lihatJasa']);
Route::get('profile', [App\Http\Controllers\API\KatalogController::class, 'profile'])->middleware('auth:sanctum');
Route::get('account', [App\Http\Controllers\API\KatalogController::class, 'account'])->middleware('auth:sanctum');

Route::get('pesan/{id}', [App\Http\Controllers\API\KatalogController::class, 'pesan'])->middleware('auth:sanctum');
Route::post('pesan/store', [App\Http\Controllers\API\KatalogController::class, 'store_pesan'])->middleware('auth:sanctum');
Route::get('pesan', [App\Http\Controllers\API\KatalogController::class, 'data_pesan'])->middleware('auth:sanctum');
Route::get('pesan_status', [App\Http\Controllers\API\KatalogController::class, 'status_pesan'])->middleware('auth:sanctum');

Route::post('penyedia_jasa/store', [App\Http\Controllers\API\KatalogController::class, 'store_penyedia_jasa'])->middleware('auth:sanctum');
