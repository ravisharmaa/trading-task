<?php

namespace App\Http\Clients;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

readonly class HttpClientFactory
{
    public function makeClient(ClientType $clientType): PendingRequest
    {
        return match ($clientType) {
            ClientType::COMPANY_SYMBOL => $this->makeCompanyClient(),
            ClientType::HISTORICAL_DATA => $this->makeFinancialClient(),
        };
    }

    private function makeCompanyClient(): PendingRequest
    {
        return Http::baseUrl(config('app.company_symbols_base_url'));
    }

    private function makeFinancialClient(): PendingRequest
    {
        return Http::baseUrl(env('FINANCE_RAPID_API'))
                ->withHeaders(
                    [
                    'X-RapidAPI-Key' => config('app.finance_api_key'),
                    'X-RapidAPI-HOST' => config('app.finance_api_host'),
                    ]
                );
    }
}
