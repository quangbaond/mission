<?php

use App\Http\Controllers\UserController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// login

Route::prefix('auth')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Auth\Api\LoginController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\Auth\Api\RegisterController::class, 'register']);
});

Route::prefix('v1')->middleware(['auth:api'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile', [UserController::class, 'updateProfile']);
    Route::get('/introduce', [UserController::class, 'getIntroduce']);
    Route::get('/missions', [\App\Http\Controllers\MissionController::class, 'index']);
    Route::post('/missions/do-task', [\App\Http\Controllers\MissionController::class, 'doTask']);
    Route::post('/withdraw', [\App\Http\Controllers\UserController::class, 'withDraw']);
    Route::get('/withdraw', [\App\Http\Controllers\UserController::class, 'getWithDraw']);
});
