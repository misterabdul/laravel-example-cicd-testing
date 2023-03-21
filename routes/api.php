<?php

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

Route::get('user/public/{user?}', \App\Http\Controllers\Api\User\PublicUserController::class);
Route::get('category/public/{category?}', \App\Http\Controllers\Api\Category\PublicCategoryController::class);
Route::get('post/public/{post?}', \App\Http\Controllers\Api\Post\PublicPostController::class);

Route::middleware('auth:api')->group(function () {
    Route::get('me', \App\Http\Controllers\Api\User\MeController::class);
    Route::put('me', \App\Http\Controllers\Api\User\MeUpdateController::class);
    Route::apiResource('user', \App\Http\Controllers\Api\User\UserController::class);

    Route::apiResource('role', \App\Http\Controllers\Api\Role\RoleController::class);

    Route::apiResource('category', \App\Http\Controllers\Api\Category\CategoryController::class);

    Route::get('post/mine', \App\Http\Controllers\Api\Post\MyPostController::class);
    Route::get('post/{post}/publish', \App\Http\Controllers\Api\Post\PublishController::class);
    Route::apiResource('post', \App\Http\Controllers\Api\Post\PostController::class);

    Route::apiResource('post.comment', \App\Http\Controllers\Api\Comment\PostCommentController::class);
    Route::apiResource('comment', \App\Http\Controllers\Api\Comment\CommentController::class);
});
