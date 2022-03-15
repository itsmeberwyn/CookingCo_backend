<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
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

// Route::post('/register', [AuthController::class, 'store'])->name('register.store');
// Route::get('/test', [AuthController::class, 'index'])->name('register.index')->middleware(['auth:sanctum']);
// Route::get('/test', [RegisterController::class, 'index'])->name('register.index');
// Route::post('/login', [AuthController::class, 'index'])->name('login.index');


Route::resource('auth', AuthController::class);


// NOTE: next job!
Route::group(['middleware' => ['auth:sanctum']], function () {
    // insert all of the secured routes here...
    Route::post("/post", [PostController::class, 'store'])->name('post.store');
    Route::get("/post", [PostController::class, 'index'])->name('post.index');
});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
