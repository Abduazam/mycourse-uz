<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

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
Route::post('/telegram/index', [\App\Http\Controllers\Telegram\TelegramController::class, 'index'])->name('telegram.index');
//Route::post('/telegram/index', function () {
//    info("hee");
//})->name('telegram.index');