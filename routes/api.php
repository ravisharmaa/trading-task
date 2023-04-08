<?php

use App\Http\Controllers\Api\HistoricalQuoteController;
use Illuminate\Support\Facades\Route;

Route::name('historical')
    ->apiResource('historical/quote', HistoricalQuoteController::class)
    ->only(['index', 'store']);
