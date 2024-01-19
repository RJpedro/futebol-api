<?php

use App\Http\Controllers\Api\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Player Routes 
|--------------------------------------------------------------------------
|
| Here you have all the routes related to the teams
|
*/

Route::prefix('v1')->group(function () {
    Route::apiResource('/team', TeamController::class);
});