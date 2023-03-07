<?php

use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::middleware('guest')->group(function (){
    Route::post('/login', [WebAuthController::class, 'logIn']);
    Route::post('/register', [WebAuthController::class, 'registerUser']);
});
Route::get('/logout', [WebAuthController::class, 'logOut']);

// Route::middleware("auth")->group(function () {
//     Route::get('/home', function () {
//         return Inertia::render("Home");
//     });
//     Route::get('/materi', function () {
//         return Inertia::render("MateriSementara");
//     });
//     Route::get('/profile', function () {
//         return Inertia::render("Profile");
//     });
// });
