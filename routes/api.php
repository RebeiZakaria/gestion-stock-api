<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EquipementController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\AffectationController;

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

// Routes d'authentification (publiques)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    // Route pour récupérer les infos utilisateur
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Routes pour les équipements (tous les utilisateurs connectés peuvent consulter)
    Route::get('/equipements', [EquipementController::class, 'index']);
    Route::get('/equipements/{id}', [EquipementController::class, 'show']);

    // Routes pour les affectations
    Route::get('/affectations', [AffectationController::class, 'index']);
    Route::post('/affectations', [AffectationController::class, 'store']);
    Route::put('/affectations/{id}/retourner', [AffectationController::class, 'retourner']);

    
    // Route pour obtenir les utilisateurs
    Route::get('/users', [AuthController::class, 'getAllUsers']);
    
    // Route pour obtenir un équipement avec ses affectations
    Route::get('/equipments/{id}/affectations', [EquipementController::class, 'getWithAffectations']);

    // Routes des statistiques (accessibles à tous les utilisateurs connectés)
    Route::prefix('statistics')->group(function () {
        Route::get('/overview', [StatisticsController::class, 'overview']);
        Route::get('/by-type', [StatisticsController::class, 'byType']);
        Route::get('/by-brand', [StatisticsController::class, 'byBrand']);
        Route::get('/purchase-evolution', [StatisticsController::class, 'purchaseEvolution']);
        Route::get('/age-analysis', [StatisticsController::class, 'ageAnalysis']);
        Route::get('/recent-old', [StatisticsController::class, 'recentAndOldEquipments']);
        Route::get('/dashboard', [StatisticsController::class, 'dashboard']);
    });
    
    // Routes réservées aux admins seulement
    Route::middleware('admin')->group(function () {
        Route::post('/equipements', [EquipementController::class, 'store']);
        Route::put('/equipements/{id}', [EquipementController::class, 'update']);
        Route::delete('/equipements/{id}', [EquipementController::class, 'destroy']);
    });
});