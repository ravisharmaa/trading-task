<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidHttpRequest;
use App\Http\Clients\ClientType;
use App\Http\Clients\HttpClientService;
use App\Http\Controllers\Controller;
use App\Http\Requests\HistoricalQuoteRequest;
use App\Http\Resources\HistoricalDataResource;
use App\Http\ValueObjects\MailMessageValueObject;
use App\Jobs\SendStatistics;

class HistoricalQuoteController extends Controller
{
    public function show(
        HistoricalQuoteRequest $request,
        HttpClientService $clientService
    ): HistoricalDataResource {
        $fianceApiUrl = sprintf(env('FINANCE_API_PATH_COMPONENT'), $request->get('company_symbol'));
        try {
            $data = $clientService->getData(ClientType::HISTORICAL_DATA, $fianceApiUrl);
        } catch (InvalidHttpRequest) {
            return new HistoricalDataResource([]);
        }
        dispatch(new SendStatistics(
            new MailMessageValueObject(
                $request->get('company_symbol'),
                $request->get('start_date'),
                $request->get('end_date')
            )
        ));

        return new HistoricalDataResource($data);
    }
}
