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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('academy_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null');

            // Financial breakdown
            $table->integer('class_count');
            $table->decimal('base_price', 10, 2); // Before discount and VAT
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('vat_percent', 5, 2)->default(5.00);
            $table->decimal('vat_amount', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2); // After discount + VAT
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->decimal('remaining_amount', 10, 2);

            // Meta fields
            $table->string('currency', 3)->default('AED');
            $table->enum('status', ['pending', 'partial', 'paid'])->default('pending');
            $table->string('status_student')->nullable(); // active, dropped, etc.
            $table->text('note')->nullable();

            // Dates
            $table->date('payment_date')->nullable(); // when actually paid
            $table->date('start_date')->nullable();   // course start
            $table->date('end_date')->nullable();     // course end

            // Optional item breakdown
            $table->json('items')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
