<?php

use App\Http\Controllers\Admin\BanUserController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ReportUsersController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ReportCommentsController;
use App\Http\Controllers\Admin\ReportPostsController;
use App\Http\Controllers\Admin\WarnUserController;
use Illuminate\Support\Facades\Auth;
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


Route::get('/', function () {
    return view('welcome');
});

Route::get('/user/{user_id}', [UserController::class, 'index'])->name('user');
Route::get('/post/{post_id}', [PostController::class, 'index'])->name('post');
Route::get('/report-users', [ReportUsersController::class, 'index'])->name('reportuser');
Route::get('/report-posts', [ReportPostsController::class, 'index'])->name('reportpost');
Route::get('/report-comments', [ReportCommentsController::class, 'index'])->name('reportcomment');
Route::get('/warn-user', [WarnUserController::class, 'create'])->name('warn');
Route::post('/ban-user', [BanUserController::class, 'create'])->name('ban');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
