<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\KoleksiController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebAuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;

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
    Route::apiResource('posts', PostController::class);
    Route::apiResource('replies', ReplyController::class);
    Route::apiResource('subjects', SubjectController::class);

    Route::get('/me', function (Request $request) {
        $res = User::with(['level','registeredUser', 'koleksi'])->where('id', $request->user()->id)->first();
        // $res = $request->user()->id;
        return $res;
    });
    Route::prefix("me")->group(function () {
        Route::get('/materials', [MaterialController::class, 'owned']);
        Route::get('/posts', [PostController::class, 'owned']);
        Route::get('/koleksi', [KoleksiController::class, 'owned']);
        Route::post('/koleksi/{matid}', [KoleksiController::class, 'addKoleksi']);
        Route::delete('/koleksi/{matid}', [KoleksiController::class, 'removeKoleksi']);
        // Route::get('/ngawi', function(){
        //     return Hash::make("ngawi123");
        // });
    });
});

Route::get('/ypga', function() {
    return Hash::make("akuyoga123");
});

Route::prefix('auth')->group(function () {
    Route::post('/reginfo', [AuthController::class, 'regInfo']);
    Route::post("/register", [AuthController::class, 'registerUser']);
    Route::post("/login", [AuthController::class, 'loginUser']);
    Route::middleware('auth:sanctum')->get("/logout", [AuthController::class, 'logOutUser']);
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        $res = User::with(['level','registeredUser'])->where('id', $request->user()->id)->first();
        // $res = $request->user()->id;
        return $res;
    });
    Route::prefix("web")->middleware('web')->group(function () {
        Route::middleware('guest')->group(function (){
            Route::post('/login', [WebAuthController::class, 'logIn']);
            Route::post('/register', [WebAuthController::class, 'registerUser']);
        });
        Route::middleware('auth')->get('/logout', [WebAuthController::class, 'logOut']);
    });
});
