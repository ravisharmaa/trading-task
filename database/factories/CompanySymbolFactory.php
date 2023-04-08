<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanySymbolFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'market_category' => fake()->text,
            'round_lot_size' => fake()->randomDigitNotNull,
            'security_name' => fake()->text,
            'symbol' => fake()->text,
            'test_issue' => fake()->text,
        ];
    }
}
