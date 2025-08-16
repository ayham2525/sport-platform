<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // For date filtering
            $table->index('payment_date', 'payments_payment_date_idx');

            // Common filters
            $table->index(['status', 'category'], 'payments_status_category_idx');
            $table->index('branch_id', 'payments_branch_id_idx');
            $table->index('academy_id', 'payments_academy_id_idx');
            $table->index('program_id', 'payments_program_id_idx');
            $table->index('player_id', 'payments_player_id_idx');
            $table->index('payment_method_id', 'payments_method_id_idx');

            // Sometimes useful for quick lookup
            $table->index('reset_number', 'payments_reset_number_idx');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_payment_date_idx');
            $table->dropIndex('payments_status_category_idx');
            $table->dropIndex('payments_branch_id_idx');
            $table->dropIndex('payments_academy_id_idx');
            $table->dropIndex('payments_program_id_idx');
            $table->dropIndex('payments_player_id_idx');
            $table->dropIndex('payments_method_id_idx');
            $table->dropIndex('payments_reset_number_idx');
        });
    }
};
