<?php

use App\Http\Controllers\Api\UserController;
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
    Route::apiResource('/user', UserController::class);
});