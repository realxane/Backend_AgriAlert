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
use App\Http\Controllers\Api\Auth\SocialAuthController;

use App\Http\Controllers\Api\IA\ChatBotController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\WeatherHistoryController;
use App\Http\Controllers\Api\IA\DiagnosticController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ChampController;
use App\Http\Controllers\Api\PlantationController;
use App\Http\Controllers\Api\ActiviteAgricoleController;

use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\CultureController;

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
    //connexion via les réseaux sociaux
    Route::post('/{provider}/callback', [SocialAuthController::class, 'handleSocialLogin'])->where('provider', 'google|facebook');
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

Route::middleware('auth:sanctum')->group(function () {

    // Profil utilisateur
    Route::get('/me', [ProfileController::class, 'show']);
    Route::put('/me', [ProfileController::class, 'update']);
    Route::put('/me/password', [ProfileController::class, 'updatePassword']);
    Route::post('/me/photo', [ProfileController::class, 'updatePhoto']); 

    // Champ 
    Route::get('/champs', [ChampController::class, 'index']);
    Route::get('/champs/{champ}', [ChampController::class, 'show']);
    Route::put('/champs/{champ}', [ChampController::class, 'update']);

    // Plantations (cultures associées à un champ)
    Route::post('/champs/{champ}/plantations', [PlantationController::class, 'store']);     // ajouter culture
    Route::put('/plantations/{plantation}', [PlantationController::class, 'update']);      // modifier dates
    Route::delete('/plantations/{plantation}', [PlantationController::class, 'destroy']);  // supprimer culture

    // Activités agricoles (Semis / Récolte / Traitement) 
    Route::post('/plantations/{plantation}/activites', [ActiviteAgricoleController::class, 'store']);
    Route::get('/plantations/{plantation}/activites', [ActiviteAgricoleController::class, 'index']);
});


Route::get('/regions', [RegionController::class, 'index']);

Route::get('/cultures', [CultureController::class, 'index']);


