<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\GenresController;
use App\Http\Controllers\API\CastController;
use App\Http\Controllers\API\MoviesController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\CastMovieController;

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

Route::prefix('v1')->group(function () {
    Route::apiResource('genre', GenresController::class);
    Route::apiResource('cast', CastController::class);
    Route::apiResource('movie', MoviesController::class);
    Route::apiResource('role', RoleController::class)->middleware(['auth:api', 'isAdmin']);
    Route::apiResource('cast-movie', CastMovieController::class);

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::get('me', [AuthController::class, 'getUserLogged'])->middleware('auth:api');
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('verification-email', [AuthController::class, 'verification'])->middleware('auth:api');
        Route::post('generate-otp-code', [AuthController::class, 'generate_otp'])->middleware('auth:api');
    })->middleware('api');

    Route::post('profile', [ProfileController::class, 'updateOrCreate'])->middleware(['auth:api', 'isVerified']);
    Route::post('review', [ReviewController::class, 'storeUpdate'])->middleware(['auth:api', 'isVerified']);
});
