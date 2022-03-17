<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['web']], function () {
    Route::get("/login/google", [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get("/login/google/callback", [AuthController::class, 'handleGoogleCallback']);

    Route::get("/login/facebook", [AuthController::class, 'redirectToFacebook'])->name('login.facebook');
    Route::get("/login/facebook/callback", [AuthController::class, 'handleFacebookCallback']);
});

Route::get('/', function () {
    return view('welcome');
});
