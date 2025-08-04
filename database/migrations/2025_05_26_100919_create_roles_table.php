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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');                     // e.g., Admin, Staff, User
            $table->string('slug');                     // e.g., admin, staff, user
            $table->foreignId('system_id')->nullable()  // nullable for global roles like full_admin
                  ->constrained('systems')
                  ->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['slug', 'system_id']); // Composite unique constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
