<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'full_admin',
                'system_admin',
                'branch_admin',
                'academy_admin',
                'employee',
                'coach',
                'player'
            ])->default('player')->after('password');

            $table->unsignedBigInteger('system_id')->nullable()->after('role');

            $table->foreign('system_id')->references('id')->on('systems')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['system_id']);
            $table->dropColumn('system_id');
            $table->dropColumn('role');
        });
    }
};
