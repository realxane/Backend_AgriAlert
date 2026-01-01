<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//-----------------login(NB:A effacer avant de prendre les modification de Alassane -------------------------
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
//-------------------fin login---------------------

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
