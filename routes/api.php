<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
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



// Route::resource('auth', AuthController::class);
Route::get("/test", [AuthController::class, 'test'])->name('test');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/loginsocial', [AuthController::class, 'registerOrLoginUser'])->name('socialauth');

// NOTE: next job!
Route::group(['middleware' => ['auth:sanctum']], function () {
    // insert all of the secured routes here...
    // post
    Route::post("/post", [PostController::class, 'store'])->name('post.store');
    Route::get("/post/{post_id}", [PostController::class, 'show'])->name('post.show');
    Route::get("/profile/{user_id?}", [PostController::class, 'index'])->name('post.index');
    Route::get("/recipe/{user_id?}", [PostController::class, 'getDataRecipe'])->name('post.recipe');
    Route::post("/updatepost", [PostController::class, 'patch'])->name('post.patch');

    // comment
    Route::patch("/comment/{id}", [CommentController::class, 'update'])->name('comment.update');
    Route::get("/comment", [CommentController::class, 'index'])->name('comment.index');
    Route::post("/comment", [CommentController::class, 'store'])->name('comment.store');
    Route::post("/remove/comment/{id}", [CommentController::class, 'remove'])->name('comment.delete');

    // rate
    Route::get("/rate", [RatingController::class, 'index'])->name('rate.index');
    Route::get("/userratepost", [RatingController::class, 'userRatePost']);
    Route::post("/rate", [RatingController::class, 'store'])->name('rate.store');

    // profile 
    Route::get("/info", [ProfileController::class, 'index'])->name('profile.index');
    Route::post("/updateprofile", [ProfileController::class, 'patch'])->name('profile.patch');

    // feeds
    Route::get("/feed", [FeedController::class, 'index'])->name('feed.index');

    // search
    Route::get("/search", [FeedController::class, 'search'])->name('feed.search');
    Route::get("/popular", [FeedController::class, 'popularPost'])->name('feed.popular');
    Route::get("/randomrecipe", [FeedController::class, 'getRandomRecipe'])->name('feed.randomrecipe');

    // follow
    Route::get("/countfollows/{user_id?}", [FollowController::class, 'countFollows']);
    Route::get("/followers/{user_id?}", [FollowController::class, 'getFollowers'])->name('followers');
    Route::get("/followings/{user_id?}", [FollowController::class, 'getFollowings'])->name('followings');
    Route::get("/isfollow/{user_id}", [FollowController::class, 'isfollow']);

    Route::post("/follow", [FollowController::class, 'follow'])->name('follow');
    Route::delete("/unfollow/{following_id}", [FollowController::class, 'unfollow'])->name('unfollow');

    // bookmark
    Route::get("/checkuserbookmark/{post_id}", [BookmarkController::class, 'checkUserBookmark']);
    Route::post("/removebookmark", [BookmarkController::class, 'destroy'])->name('bookmark.destroy');
    Route::post("/bookmark", [BookmarkController::class, 'store'])->name('bookmark.store');
    Route::get("/bookmark", [BookmarkController::class, 'index'])->name('bookmark.index');
});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
