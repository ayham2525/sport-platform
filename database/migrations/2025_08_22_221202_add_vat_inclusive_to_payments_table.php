<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // VAT inclusive flag (true = prices already include VAT)
            $table->boolean('is_vat_inclusive')
                  ->default(true)
                  ->after('vat_amount')
                  ->comment('1 = VAT inclusive, 0 = VAT exclusive');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('is_vat_inclusive');
        });
    }
};
