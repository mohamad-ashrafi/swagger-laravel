<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\PostController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('user')->group(function () {
    Route::get('/',[HomeController::class,'index']);
    Route::post('/register' ,[ AuthController::class , 'register' ]);
    Route::post('/login' ,[ AuthController::class , 'login' ]);
    Route::post('/verify-otp' ,[ AuthController::class , 'verifyOtp' ]);
    Route::post('/resend-otp' ,[ AuthController::class , 'resendOtp' ]);
    Route::get('/logout' ,[ AuthController::class , 'logout' ]);
    Route::get('/show/{id}', [UserController::class, 'show']);

});


Route::prefix('user')->middleware(['auth:api'])->group(function (){
    Route::prefix('post')->group(function (){
        Route::post('/create',[PostController::class,'store']);
        Route::post('/{id}/like',[PostController::class,'increaseLink']);
    });
    Route::prefix('profile')->group(function (){
        Route::post('/show/{id}',[UserController::class,'show']);
    });
});



