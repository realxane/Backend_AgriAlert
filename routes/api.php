<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterAgriculteurStep1Controller;
use App\Http\Controllers\Api\Auth\RegisterAgriculteurStep2Controller;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;

Route::prefix('auth')->group(function () {
    // Agriculteur : inscription en 2 étapes
    Route::post('register/agriculteur/step1', RegisterAgriculteurStep1Controller::class);
    Route::post('register/agriculteur/step2', RegisterAgriculteurStep2Controller::class);

    // Connexion (agriculteur + admin)
    Route::post('login', LoginController::class);

    // Déconnexion
    Route::middleware('auth:sanctum')->post('logout', LogoutController::class);

    Route::post('forgot-password', ForgotPasswordController::class);
    Route::post('reset-password', ResetPasswordController::class);
});