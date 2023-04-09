<?php

namespace App\Http\Clients;

use App\Exceptions\InvalidHttpRequest;
use Psr\Log\LoggerInterface;

class HttpClientService
{
    public function __construct(
        private readonly HttpClientFactory $clientFactory,
        private readonly LoggerInterface $logger
    ) {
    }

    public function getData(ClientType $clientType, string $url): ?array
    {
        $client = $this->clientFactory->makeClient($clientType);
        try {
            $response = $client->get($url);
        } catch (\Exception $exception) {
            $this->logger->info('Error while getting company symbols data.');

            return [];
        }

        if (! $response->successful()) {
            throw new InvalidHttpRequest();
        }

        return $response->json() ?? [];
    }
}
