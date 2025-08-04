<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // view, create, update, delete, export, etc.
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('model_id')->constrained('models')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['action', 'role_id', 'model_id']); // ensure no duplicate permission per role/model
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};

