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
use App\Models\CompanySymbol;

class HistoricalQuoteController extends Controller
{
    public function show(
        HistoricalQuoteRequest $request,
        HttpClientService $clientService
    ): HistoricalDataResource {
        $financeApiUrl = sprintf(config('app.finance_api_path_component'), $request->get('company_symbol'));
        try {
            $data = $clientService->getData(ClientType::HISTORICAL_DATA, $financeApiUrl);
        } catch (InvalidHttpRequest) {
            return new HistoricalDataResource([]);
        }
        SendStatistics::dispatchIf(
            !empty($data['prices']), new MailMessageValueObject(
                CompanySymbol::whereSymbol($request->get('company_symbol'))->first()->name,
                $request->get('start_date'),
                $request->get('end_date'),
                $request->get('email'),
                $data['prices']
            )
        );

        return new HistoricalDataResource($data['prices'] ?? []);
    }
}
