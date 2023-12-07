<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\AuthenticationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/users/{id}', [AuthenticationController::class, 'update']);
    Route::delete('/users/{id}', [AuthenticationController::class, 'destroy']);

    Route::get('/logout', [AuthenticationController::class, 'logout']);

    Route::get("/test", [TelegramController::class, 'test']);

    Route::get('/set-webhook', [TelegramController::class, 'setWebhook']);
    Route::get('/del-webhook', [TelegramController::class, 'delWebhook']);
});
Route::get("/get-update", [TelegramController::class, 'getUpdate']);


Route::post("/webhook", [TelegramController::class, 'webhook']);

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
