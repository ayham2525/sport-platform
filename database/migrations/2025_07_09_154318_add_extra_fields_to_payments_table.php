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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'reset_number')) {
                $table->string('reset_number')->nullable();
            }

            if (!Schema::hasColumn('payments', 'class_time_from')) {
                $table->time('class_time_from')->nullable();
            }

            if (!Schema::hasColumn('payments', 'class_time_to')) {
                $table->time('class_time_to')->nullable();
            }

            if (!Schema::hasColumn('payments', 'category')) {
                $table->enum('category', ['program', 'uniform', 'asset', 'camp'])->default('program');
            }

            if (!Schema::hasColumn('payments', 'receipt_path')) {
                $table->string('receipt_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'reset_number')) {
                $table->dropColumn('reset_number');
            }

            if (Schema::hasColumn('payments', 'class_time_from')) {
                $table->dropColumn('class_time_from');
            }

            if (Schema::hasColumn('payments', 'class_time_to')) {
                $table->dropColumn('class_time_to');
            }

            if (Schema::hasColumn('payments', 'category')) {
                $table->dropColumn('category');
            }

            if (Schema::hasColumn('payments', 'receipt_path')) {
                $table->dropColumn('receipt_path');
            }
        });
    }
};
