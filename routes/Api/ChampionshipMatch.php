<?php

use App\Http\Controllers\Api\ChampionshipMatchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Player Routes 
|--------------------------------------------------------------------------
|
| Here you have all the routes related to the championship matches
|
*/

Route::prefix('v1')->group(function () {
    Route::apiResource('/championship-match', ChampionshipMatchController::class);
});