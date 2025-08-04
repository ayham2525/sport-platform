<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Add the original currency code
            $table->string('original_currency', 3)->nullable()->after('currency');
            // Add the exchange rate used for conversion to base currency
            $table->decimal('exchange_rate_used', 15, 8)->nullable()->after('original_currency');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['original_currency', 'exchange_rate_used']);
        });
    }
};
