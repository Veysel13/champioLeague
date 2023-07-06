<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\League\ScoreController;

Route::get('/', [ScoreController::class, 'index'])->name('score.index');
Route::post('/play', [ScoreController::class, 'play'])->name('score.play');
Route::any('/xhrWeekScore', [ScoreController::class, 'xhrWeekScore'])->name('score.xhrWeekScore');


