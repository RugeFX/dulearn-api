<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware('cors')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('materials', MaterialController::class);
    });
    Route::prefix('auth')->group(function () {
        Route::post("/register", [AuthController::class, 'registerUser']);
        Route::post("/login", [AuthController::class, 'loginUser']);
        Route::get("/logout", [AuthController::class, 'logOutUser']);
        Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
            return $request->user();
        });
    });
});
