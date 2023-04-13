<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\PostImageController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::apiResource('users', UserController::class);
    Route::get('/users/{user}/following', [UserController::class, 'followingToggle']);
    Route::get('/users/stat/{userId?}', [UserController::class, 'userStat']);
    Route::get('/users/following/posts', [UserController::class, 'followingPosts']);
    Route::apiResource('posts', PostController::class);
    Route::post('/posts/{post}/like', [PostController::class, 'likeToggle']);
    Route::post('/posts/{post}/repost', [PostController::class, 'repost']);
    Route::post('/posts/{post}/storeComment', [PostController::class, 'storeComment']);
    Route::get('/posts/{post}/getComments', [PostController::class, 'getComments']);
    Route::apiResource('postImages', PostImageController::class);
});