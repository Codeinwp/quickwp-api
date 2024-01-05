<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OpenAIController;

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

Route::post('/wizard/start', [OpenAIController::class, 'start']);
Route::post('/wizard/send', [OpenAIController::class, 'send']);
Route::get('/wizard/status', [OpenAIController::class, 'status']);
Route::get('/wizard/get', [OpenAIController::class, 'get']);
Route::get('/wizard/images', [ImageController::class, 'search']);
