<?php

namespace Tests\Unit;

use App\Http\Clients\ClientType;
use App\Http\Clients\HttpClientFactory;
use Illuminate\Http\Client\PendingRequest;
use Tests\TestCase;

class HttpClientFactoryTest extends TestCase
{
    public function test_it_should_provide_a_client()
    {
        $clientFactory = new HttpClientFactory();
        $client = $clientFactory->makeClient(ClientType::HISTORICAL_DATA);
        $this->assertInstanceOf(PendingRequest::class, $client);
    }
}
