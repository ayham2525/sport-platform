<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency', 3);   // e.g., USD
            $table->string('target_currency', 3); // e.g., AED
            $table->decimal('rate', 18, 8);       // High precision rate
            $table->timestamp('fetched_at')->useCurrent(); // When this rate was retrieved
            $table->timestamps();

            $table->index(['base_currency', 'target_currency', 'fetched_at'], 'exchange_rate_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
