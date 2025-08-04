<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sports', function (Blueprint $table) {
            $table->id();
            $table->string('name_en')->unique(); // English name
            $table->string('name_ar')->nullable(); // Arabic name
            $table->text('description')->nullable(); // optional description
            $table->string('icon')->nullable(); // optional icon path or class
            $table->boolean('is_active')->default(true); // status flag
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sports');
    }
};
