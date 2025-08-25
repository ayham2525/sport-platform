<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uniform_requests', function (Blueprint $table) {
            $table->enum('stock_status', [
                'in_stock',
                'out_of_stock',
                'reserved',
                'pending'
            ])->default('pending')->after('office_status');
        });
    }

    public function down(): void
    {
        Schema::table('uniform_requests', function (Blueprint $table) {
            $table->dropColumn('stock_status');
        });
    }
};
