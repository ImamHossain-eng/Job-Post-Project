<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LogoutController;

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

//Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Protected Route Logout Route
Route::group(['middleware'=>'auth:sanctum'], function(){
    Route::post('/logout', [LogoutController::class, 'logout']);
});

//Protected Route for authenticated user
Route::group(['prefix'=>'user', 'middleware'=>['auth:sanctum', 'user']], function(){
    Route::get('/test', [UserController::class, 'test']);
    Route::get('/posts', [UserController::class, 'post_index']);
    Route::get('/post/{id}', [UserController::class, 'post_show']);
});


//Protected Route for Admin
Route::group(['prefix'=>'admin', 'middleware'=>['auth:sanctum', 'admin']], function(){
    Route::get('/test', [AdminController::class, 'test']);
    Route::get('/posts', [AdminController::class, 'post_index']);
    Route::get('/post/{id}', [AdminController::class, 'post_show']);
    Route::post('/post', [AdminController::class, 'post_store']);
    Route::put('/post/{id}', [AdminController::class, 'post_update']);
    Route::delete('post/{id}', [AdminController::class, 'post_destroy']);

    //Create admin user
    Route::post('/create', [AdminController::class, 'admin_store']);
    
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
