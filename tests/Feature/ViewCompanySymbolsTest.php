<?php

namespace Tests\Feature;

use App\Models\CompanySymbol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewCompanySymbolsTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_should_respond_with_company_symbols()
    {
        CompanySymbol::factory()->create();
        $this->getJson(route('company-symbols.index'))
            ->assertOk();
    }
}
