<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CVController;

Route::get('/', function () {
    return redirect()->route('cv.index');
});

// Простая проверка состояния для хелсчека (Render / health probe)
Route::match(['GET', 'HEAD'], '/healthz', function () {
    return response('OK', 200);
});

Route::prefix('cv')->name('cv.')->group(function () {
    Route::get('/', [CVController::class, 'index'])->name('index');
    Route::post('/store', [CVController::class, 'store'])->name('store');
    Route::get('/preview', [CVController::class, 'preview'])->name('preview');
    Route::get('/download', [CVController::class, 'download'])->name('download');
    Route::get('/list', [CVController::class, 'list'])->name('list');
    Route::get('/load/{id}', [CVController::class, 'load'])->name('load');
});
