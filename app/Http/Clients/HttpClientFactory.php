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
        return Http::baseUrl(env('COMPANY_SYMBOLS_BASE_URL'));
    }

    private function makeFinancialClient(): PendingRequest
    {
        return Http::baseUrl(env('FINANCE_RAPID_API'))
                ->withHeaders(
                    [
                    'X-RapidAPI-Key' => env('FINANCE_RAPID_API_KEY'),
                    'X-RapidAPI-HOST' => env('FINANCE_RAPID_API_HOST'),
                    ]
                );
    }
}
