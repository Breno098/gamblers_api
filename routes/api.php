<?php

use App\Http\Controllers\Adm\API\CompetitionController;
use App\Http\Controllers\Adm\API\CountryController;
use App\Http\Controllers\Adm\API\GameController;
use App\Http\Controllers\Adm\API\PlayerController;
use App\Http\Controllers\Adm\API\StadiumController;
use App\Http\Controllers\Adm\API\TeamController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);



Route::middleware('auth:api')->group(function () {
    Route::middleware('user.adm')->group(function () {
        Route::apiResource('game', GameController::class);
        Route::apiResource('team', TeamController::class);
        Route::apiResource('country', CountryController::class);
        Route::apiResource('player', PlayerController::class);
        Route::apiResource('stadium', StadiumController::class);
        Route::apiResource('competition', CompetitionController::class);

         /** UPDATES WITH IMAGE */
        Route::post('/team/updateWithImage/{team}', [TeamController::class, 'updateWithImage']);
        Route::post('/competition/updateWithImage/{competition}', [CompetitionController::class, 'updateWithImage']);
    });
});
