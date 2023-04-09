<?php

use App\Http\Controllers\Api\CompanySymbolController;
use App\Http\Controllers\Api\HistoricalQuoteController;
use Illuminate\Support\Facades\Route;

Route::apiResource('company-symbols', CompanySymbolController::class)
    ->only('index');
Route::post('historical-quote', [ HistoricalQuoteController::class, 'show'])
    ->name('historical.quote.show');
