<?php

namespace Tests\Unit;

use App\Exceptions\InvalidHttpRequest;
use App\Http\Clients\ClientType;
use App\Http\Clients\HttpClientFactory;
use App\Http\Clients\HttpClientService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class HttpClientServiceTest extends TestCase
{
    public function test_it_should_send_http_request()
    {
        Http::fake();
        $clientFactory = new HttpClientFactory();
        $httpClientService = new HttpClientService($clientFactory, Log::getLogger());
        $httpClientService->getData(ClientType::COMPANY_SYMBOL, 'some-random-url');
        Http::assertSentCount(1);
    }

    public function test_it_should_throw_exception_when_the_request_fails()
    {
        $this->expectException(InvalidHttpRequest::class);
        Http::fake([
            '*' => Http::response([], 400)
        ]);
        $clientFactory = new HttpClientFactory();
        $httpClientService = new HttpClientService($clientFactory, Log::getLogger());
        $httpClientService->getData(ClientType::COMPANY_SYMBOL, 'some-random-url');
        Http::assertSentCount(1);
    }

    public function test_it_should_return_response_array()
    {
        Http::fake([
            '*' => Http::response([
                'some-sample-data'
            ])
        ]);
        $clientFactory = new HttpClientFactory();
        $httpClientService = new HttpClientService($clientFactory, Log::getLogger());
        $data = $httpClientService->getData(ClientType::COMPANY_SYMBOL, 'some-random-url');
        Http::assertSentCount(1);
        $this->assertCount(1, $data);
    }
}
