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
        Schema::table('uniform_requests', function (Blueprint $table) {
            $table->enum('branch_status', ['requested', 'approved', 'rejected', 'cancelled' , 'non' , 'received' , 'ordered'])
                  ->default('requested')
                  ->after('status');

            $table->enum('office_status', ['pending', 'processing', 'completed', 'cancelled' , 'delivered' , 'received' , 'non'])
                  ->default('pending')
                  ->after('branch_status');

            $table->string('payment_method', 256)
                  ->nullable()
                  ->after('office_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uniform_requests', function (Blueprint $table) {
            $table->dropColumn(['branch_status', 'office_status', 'payment_method']);
        });
    }
};
