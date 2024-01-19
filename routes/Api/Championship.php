<?php

use App\Http\Controllers\Api\ChampionshipController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Player Routes 
|--------------------------------------------------------------------------
|
| Here you have all the routes related to the championship 
|
*/

Route::prefix('v1')->group(function () {
    Route::apiResource('/championship', ChampionshipController::class);
});