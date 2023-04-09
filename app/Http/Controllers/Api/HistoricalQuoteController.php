<?php

namespace App\Http\Controllers\Api;

use App\Http\Clients\ClientType;
use App\Http\Clients\HttpClientService;
use App\Http\Controllers\Controller;
use App\Http\Requests\HistoricalQuoteRequest;
use Carbon\Carbon;

class HistoricalQuoteController extends Controller
{
    public function show(
        HistoricalQuoteRequest $request,
        HttpClientService $clientService
    ) {
        $fianceApiUrl = sprintf(env('FINANCE_API_PATH_COMPONENT'), 'AMRN');
        $data = $clientService->getData(ClientType::HISTORICAL_DATA, $fianceApiUrl);

        return collect($data['prices'])
            ->filter(function ($looped) use ($request) {
                $startDate = $request->getCarbonInstance($request->get('start_date'))->timestamp;
                $endDate = $request->getCarbonInstance($request->get('end_date'))->timestamp;
                $parsed = Carbon::createFromTimestamp($looped['date'])->timestamp;

                return $parsed >= $startDate && $parsed <= $endDate;
            });
    }
}
