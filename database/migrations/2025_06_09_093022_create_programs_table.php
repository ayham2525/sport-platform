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
       Schema::create('programs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('system_id')->constrained()->onDelete('cascade');
        $table->foreignId('branch_id')->constrained()->onDelete('cascade');
        $table->foreignId('academy_id')->constrained()->onDelete('cascade');

        $table->string('name_en');
        $table->string('name_ar')->nullable();
        $table->string('name_ur')->nullable();

        $table->integer('class_count');
        $table->decimal('price', 8, 2);
        $table->decimal('vat', 5, 2)->default(5.00);
        $table->string('currency', 3)->default('AED');

        $table->boolean('is_offer_active')->default(false);
        $table->decimal('offer_price', 8, 2)->nullable();

        $table->boolean('is_active')->default(true);

        $table->timestamps();
        $table->softDeletes();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
