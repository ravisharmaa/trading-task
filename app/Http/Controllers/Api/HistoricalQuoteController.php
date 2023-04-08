<?php

namespace App\Http\Controllers\Api;

use App\Http\Clients\ClientType;
use App\Http\Clients\HttpClientService;
use App\Http\Controllers\Controller;
use App\Http\Requests\HistoricalQuoteRequest;

class HistoricalQuoteController extends Controller
{
    public function show(
        HistoricalQuoteRequest $request,
        HttpClientService $clientService
    )
    {
        $fianceApiUrl = sprintf(env('FINANCE_API_PATH_COMPONENT'), 'AAME');
        $data = $clientService->getData(ClientType::HISTORICAL_DATA, $fianceApiUrl);
    }
}
