<?php

namespace Tests\Feature;

use App\Http\Clients\HttpClientService;
use App\Models\CompanySymbol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreCompanySymbolsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_store_company_symbols()
    {
        CompanySymbol::factory()->count(10)->create();
        $companySymbol = CompanySymbol::factory()->make();
        $clientMock = $this->createMock(HttpClientService::class);
        $this->swap(HttpClientService::class, $clientMock);
        $clientMock->method('getData')->willReturn(
            [
            [
                'Company Name' => $companySymbol->name,
                'Financial Status' => $companySymbol->financial_status,
                'Market Category' => $companySymbol->market_category,
                'Round Lot Size' => $companySymbol->round_lot_size,
                'Security Name'  => $companySymbol->security_name,
                'Test Issue'  => $companySymbol->test_issue
            ]
            ]
        );
        $this->artisan('app:store-company-symbols')->run();
        $this->assertDatabaseCount(CompanySymbol::class, 1);
    }

    public function test_it_should_not_delete_when_there_is_an_empty_response_from_symbols_api()
    {
        CompanySymbol::factory()->count(10)->create();
        $clientMock = $this->createMock(HttpClientService::class);
        $this->swap(HttpClientService::class, $clientMock);
        $clientMock->method('getData')->willReturn([]);
        $this->artisan('app:store-company-symbols')->run();
        $this->assertDatabaseCount(CompanySymbol::class, 10);
    }
}
