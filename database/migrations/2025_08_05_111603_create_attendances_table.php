<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Player or Coach
            $table->foreignId('player_id')->nullable()->constrained()->onDelete('cascade'); // Null if coach

            $table->string('branch')->nullable();
            $table->timestamp('scanned_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendances');
    }
};
