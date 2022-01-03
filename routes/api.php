<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\AdoptableController;
use App\Http\Controllers\api\AdoptionRequestController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\api\ImageController;
use App\Http\Controllers\api\ManifestController;
use App\Http\Controllers\api\OccurrenceController;
use App\Http\Controllers\api\RaceController;
use App\Http\Controllers\api\SpecieController;

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
Route::get('/', function(){return 'Aplicativo Radar Pet.';});
Route::get('/home', [HomeController::class, 'index']);

Route::prefix('occurrences')->group(function(){
    Route::get('/', function(){return 'Rota não autorizada.';});
    Route::get('/{type}', [OccurrenceController::class, 'index']);
    Route::get('/{type}/{id}', [OccurrenceController::class, 'show']);
});

Route::prefix('adoptables')->group(function(){
    Route::get('/', [AdoptableController::class, 'index']);
    Route::get('/{id}', [AdoptableController::class, 'show']);
});

Route::get('location', [OccurrenceController::class, 'location']);

Route::prefix('auth')->group(function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'store']);
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/dashboard/occurrences', [OccurrenceController::class, 'userOccurrences']);
    Route::post('/dashboard/occurrences/store', [OccurrenceController::class, 'store']);
    Route::get('/dashboard/occurrences/{id}', [OccurrenceController::class, 'userOccurrence']);
    Route::put('/dashboard/occurrences/{id}', [OccurrenceController::class, 'update']);
    Route::get('/dashboard/occurrences/{id}/manifests', [ManifestController::class, 'index']);

    Route::get('/dashboard/adoptables', [AdoptableController::class, 'userAdoptables']);
    Route::post('/dashboard/adoptables/store', [AdoptableController::class, 'store']);
    Route::get('/dashboard/adoptables/{id}', [AdoptableController::class, 'userAdoptable']);
    Route::put('/dashboard/adoptables/{id}', [AdoptableController::class, 'update']);
    Route::get('/dashboard/adoptables/{id}/requests', [AdoptionRequestController::class, 'index']);

    Route::post('/dashboard/images/upload', [ImageController::class, 'upload']);
    Route::post('/occurrences/manifests/store/{id}', [ManifestController::class, 'store']);
    Route::post('/adoptables/requests/store/{id}', [AdoptionRequestController::class, 'store']);
});

Route::get('species', [SpecieController::class, 'index']);
Route::get('races/{specie}', [RaceController::class, 'index']);