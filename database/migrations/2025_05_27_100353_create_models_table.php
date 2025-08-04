<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->foreignId('system_id')->constrained()->onDelete('cascade');
            $table->boolean('only_admin')->default(false);
            $table->timestamps();

            $table->unique(['slug', 'system_id']); // prevent duplicates per system
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('models');
    }
};
