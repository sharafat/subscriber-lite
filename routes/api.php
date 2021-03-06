<?php

use App\Http\Controllers\Api\FieldApiController;
use App\Http\Controllers\Api\SubscriberApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::as('api.')->group(function () {
    Route::apiResource('subscribers', SubscriberApiController::class);
    Route::apiResource('fields', FieldApiController::class);
});
