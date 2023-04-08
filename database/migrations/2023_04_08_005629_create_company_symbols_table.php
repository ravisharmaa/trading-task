<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_symbols', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('financial_status');
            $table->string('market_category');
            $table->float('round_lot_size');
            $table->string('security_name');
            $table->string('symbol');
            $table->string('test_issue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_symbols');
    }
};
