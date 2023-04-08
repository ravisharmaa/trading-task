<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewHistoricalQuoteTest extends TestCase
{
    use WithFaker;

    public function test_it_should_validate_form_to_view_historical_quotes()
    {
        $this->postJson(route('historical.quote.store'))
            ->assertJsonValidationErrors(['email', 'start_date', 'end_date', 'company_symbol'])
            ->assertUnprocessable();
    }

    public function test_it_requires_a_valid_email_address()
    {
        $this->postJson(route('historical.quote.store'),
            [
                'email' => fake()->text,
                'start_date' => fake()->date(),
                'end_date' => now()->addDays(10)->format('Y-m-d'),
                'company_symbol' => fake()->text,
            ])
            ->assertJsonValidationErrors(['email'])
            ->assertUnprocessable();
    }

    public function test_it_requires_the_start_date_and_end_date_to_be_a_valid_date()
    {
        $this->postJson(route('historical.quote.store'),
            [
                'email' => fake()->email,
                'start_date' => fake()->date('m-d-m-Y-H-i-s'),
                'end_date' => fake()->date('Y-md-m-Y-H-i-s'),
                'company_symbol' => fake()->text,
            ])
            ->assertJsonValidationErrors(['start_date', 'end_date'])
            ->assertUnprocessable();
    }

    public function test_that_start_date_must_be_less_than_end_date_and_less_than_current_date()
    {
        $this->postJson(route('historical.quote.store'),
            [
                'email' => fake()->email,
                'start_date' => now()->addDay()->format('Y-m-d'),
                'end_date' => fake()->date(),
                'company_symbol' => fake()->text,
            ])
            ->assertJsonValidationErrors(['start_date', 'end_date'])
            ->assertUnprocessable();
    }
}
