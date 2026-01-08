<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDashboardController;

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterAgriculteurStep1Controller;
use App\Http\Controllers\Api\Auth\RegisterAgriculteurStep2Controller;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;

use App\Http\Controllers\Api\IA\ChatBotController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\WeatherHistoryController;
use App\Http\Controllers\Api\IA\DiagnosticController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    // La gestion des utilisateurs
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::put('/users/{id}', [AdminUserController::class, 'update']);
    Route::patch('/users/{id}/status', [AdminUserController::class, 'toggleStatus']);
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);
    // Dashboard Admin(pour les statistiques(surfaces cultivées,nbre users,maladie,et alerte) et la carte)
    Route::get('/stats', [AdminDashboardController::class, 'stats']);
    Route::get('/map/champs', [AdminDashboardController::class, 'mapData']);
    Route::get('/analyser', [AdminDashboardController::class, 'analyserData']);
    // Exporter les données
    Route::get('/export/pdf', [AdminDashboardController::class, 'exportPDF']);
    Route::get('/export/excel', [AdminDashboardController::class, 'exportExcel']);

});
Route::middleware('auth:sanctum')->prefix('ia')->group(function () {
    Route::post('/chatbot', [ChatBotController::class, 'ask']);
    Route::post('/diagnostic', [DiagnosticController::class, 'detect']);
});


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

Route::middleware('auth:sanctum')->group(function () {
    // Recommandations
    Route::get('/recommendations/cultures/{culture}', [RecommendationController::class, 'byCulture']);
    // Météo
    Route::get('/weather/today', [WeatherController::class, 'today']);
    Route::get('/weather/forecast', [WeatherController::class, 'forecast']);
    Route::get('/weather/history', [WeatherHistoryController::class, 'index']);  
});

Route::post('/regions', [RegionController::class, 'store']);

