<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::post('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/login',[AuthController::class,'signin'])->name('login');


    Route::put('/users/{userId}', [UserController::class, 'updateUser']); 
    Route::get('/users/{userId}', [UserController::class, 'getUser']);



Route::get('/user/logout', [UserController::class, 'signout']);


Route::delete('/user/{id}',[UserController::class,'deleteUser']);

Route::get('/users', [UserController::class, 'getUsers']);

Route::get('/user/{id}',[UserController::class,'getUser']);

Route::post('/posts', [PostController::class, 'create']);
Route::post('/comments', [CommentController::class, 'create']);
Route::get('posts/{postId}/comments', [CommentController::class, 'getPostComments']);
Route::put('/comments/{commentId}/like', [CommentController::class, 'likeComment']);

Route::delete('/comments/{commentId}', [CommentController::class, 'deleteComment']);
Route::get('/comments', [CommentController::class, 'getComments']);