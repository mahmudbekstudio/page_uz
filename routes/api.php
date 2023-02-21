<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::group(['prefix' => 'user', 'as' => 'user.'], function() {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
    Route::put('refresh-token', [AuthController::class, 'refreshToken'])->name('refresh-token');
});

Route::group(['middleware' => ['auth:api']], function() {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function() {
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('cabinet', [AuthController::class, 'cabinet'])->name('cabinet');
    });
});