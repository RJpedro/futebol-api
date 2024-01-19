<?php

use App\Http\Controllers\Api\PlayerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Player Routes 
|--------------------------------------------------------------------------
|
| Here you have all the routes related to the players
|
*/

Route::prefix('v1')->group(function () {
    Route::apiResource('/player', PlayerController::class);
});