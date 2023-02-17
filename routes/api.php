<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebAuthController;
use App\Models\User;
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
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('materials', MaterialController::class);
});
Route::prefix('auth')->group(function () {
    Route::post('/reginfo', [AuthController::class, 'regInfo']);
    Route::post("/register", [AuthController::class, 'registerUser']);
    Route::post("/login", [AuthController::class, 'loginUser']);
    Route::get("/logout", [AuthController::class, 'logOutUser']);
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        $res = User::with(['level','registeredUser'])->where('id', $request->user()->id)->first();
        return $res;
    });
    Route::prefix("web")->group(function () {
        Route::middleware('guest')->group(function (){
            Route::post('/login', [WebAuthController::class, 'logIn']);
            Route::post('/register', [WebAuthController::class, 'registerUser']);
        });
        Route::get('/logout', [WebAuthController::class, 'logOut']);
    });
});
