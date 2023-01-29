<?php

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

Route::middleware('guest')->get("/login", function() {
    return Inertia::render("Login");
})->name("login");

Route::middleware('guest')->post('/login', [WebAuthController::class, 'logIn']);
Route::get('/logout', [WebAuthController::class, 'logOut']);

Route::middleware("auth")->get('/home', function () {
    return Inertia::render("Home");
});
