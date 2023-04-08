<?php

namespace App\Http\Clients;

use Psr\Log\LoggerInterface;

class HttpClientService
{
    public function __construct(
        private readonly HttpClientFactory $clientFactory,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function getData(ClientType $clientType, string $url): array
    {
       $client = $this->clientFactory->makeClient($clientType);
       try {
           return $client->get($url)->json();
       } catch (\Exception $exception) {
           $this->logger->info('Error while getting company symbols data.');
           return [];
       }
    }
}
