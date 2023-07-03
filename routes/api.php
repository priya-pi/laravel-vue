<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RegisterController;
// use App\Http\Controllers\RegisterController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['middleware' => 'auth:sanctum'], function () {
// });

Route::post('/login', [RegisterController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/user', [RegisterController::class, 'user']);

Route::delete('/user/{id}', [RegisterController::class, 'delete']);
Route::get('/user/{id}', [RegisterController::class, 'edit']);
Route::patch('/user/{id}', [RegisterController::class, 'update']);

Route::get('/company', [RegisterController::class, 'company']);
Route::post('/company-create', [RegisterController::class, 'create']);

