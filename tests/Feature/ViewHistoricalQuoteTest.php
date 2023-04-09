<?php

namespace Tests\Feature;

use App\Http\Clients\HttpClientService;
use App\Jobs\SendStatistics;
use App\Models\CompanySymbol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ViewHistoricalQuoteTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_it_should_validate_form_to_view_historical_quotes()
    {
        $this->postJson(route('historical.quote.show'))
            ->assertJsonValidationErrors(['email', 'start_date', 'end_date', 'company_symbol'])
            ->assertUnprocessable();
    }

    public function test_it_requires_a_valid_email_address()
    {
        $this->postJson(
            route('historical.quote.show'),
            [
                'email' => fake()->text,
                'start_date' => fake()->date(),
                'end_date' => now()->addDays(10)->format('Y-m-d'),
                'company_symbol' => fake()->text,
            ]
        )
            ->assertJsonValidationErrors(['email'])
            ->assertUnprocessable();
    }

    public function test_it_requires_the_start_date_and_end_date_to_be_a_valid_date()
    {
        $this->postJson(
            route('historical.quote.show'),
            [
                'email' => fake()->email,
                'start_date' => fake()->date('m-d-m-Y-H-i-s'),
                'end_date' => fake()->date('Y-md-m-Y-H-i-s'),
                'company_symbol' => fake()->text,
            ]
        )
            ->assertJsonValidationErrors(['start_date', 'end_date'])
            ->assertUnprocessable();
    }

    public function test_start_date_must_be_less_than_end_date_and_less_than_current_date()
    {
        $this->postJson(
            route('historical.quote.show'),
            [
                'email' => fake()->email,
                'start_date' => now()->addDay()->format('Y-m-d'),
                'end_date' => fake()->date(),
                'company_symbol' => fake()->text,
            ]
        )
            ->assertJsonValidationErrors(['start_date', 'end_date'])
            ->assertUnprocessable();
    }

    public function test_requires_a_valid_company_symbol()
    {
        $companySymbol = CompanySymbol::factory()->create();
        $clientMock = $this->createMock(HttpClientService::class);
        $this->swap(HttpClientService::class, $clientMock);
        $clientMock->method('getData')->willReturn([]);
        $this->postJson(
            route('historical.quote.show'),
            [
                'email' => fake()->email,
                'start_date' => '2023-03-15',
                'end_date' => '2023-04-08',
                'company_symbol' => $companySymbol->symbol,
            ]
        )
            ->assertOk();
    }

    public function test_it_does_not_dispatch_job_when_the_response_is_empty_from_finance_client()
    {
        Bus::fake();
        $companySymbol = CompanySymbol::factory()->create();
        $clientMock = $this->createMock(HttpClientService::class);
        $this->swap(HttpClientService::class, $clientMock);
        $clientMock->method('getData')->willReturn([]);
        $this->postJson(
            route('historical.quote.show'),
            [
                'email' => fake()->email,
                'start_date' => '2023-03-15',
                'end_date' => '2023-04-08',
                'company_symbol' => $companySymbol->symbol,
            ]
        )
            ->assertOk();
        Bus::assertNotDispatched(SendStatistics::class);
    }

    public function test_it_dispatches_job_when_the_response_is_empty_from_finance_client()
    {
        Bus::fake();
        $companySymbol = CompanySymbol::factory()->create();
        $clientMock = $this->createMock(HttpClientService::class);
        $this->swap(HttpClientService::class, $clientMock);
        $clientMock->method('getData')->willReturn(
            [
               'prices' => [
                   [
                       'date' => 1123213,
                        'open' => '1234',
                        'high' => '23213',
                        'low' => '1234'
                   ]
               ]
            ]
        );
        $this->postJson(
            route('historical.quote.show'),
            [
                'email' => fake()->email,
                'start_date' => '2023-03-15',
                'end_date' => '2023-04-08',
                'company_symbol' => $companySymbol->symbol,
            ]
        )
            ->assertOk();
        Bus::assertDispatched(SendStatistics::class);
    }
}
