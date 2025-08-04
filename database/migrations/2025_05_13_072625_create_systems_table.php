<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Add system_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('system_id')->nullable()->constrained('systems')->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Rollback system_id from users first
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['system_id']);
            $table->dropColumn('system_id');
        });

        Schema::dropIfExists('systems');
    }
};
