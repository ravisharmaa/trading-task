<?php

use App\Http\Controllers\Api\HistoricalQuoteController;
use Illuminate\Support\Facades\Route;

Route::post('historical/quote', [ HistoricalQuoteController::class, 'show'])
    ->name('historical.quote.show');
