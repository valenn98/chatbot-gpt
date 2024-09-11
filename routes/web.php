<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatBotController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('send', [ChatBotController::class, 'sendChat']);

Route::get('/update-context', [ChatBotController::class, 'showUpdateContextForm'])->name('update.context.form');
Route::post('/update-context', [ChatBotController::class, 'updateContext'])->name('update.context');

Route::get('/download-report', [ChatBotController::class, 'downloadMonthlyReport'])->name('download.report');
