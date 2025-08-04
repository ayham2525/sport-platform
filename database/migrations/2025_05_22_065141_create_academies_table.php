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
        Schema::create('academies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id'); // Foreign key to branches

            $table->string('name_en'); // Academy name in English
            $table->string('name_ar')->nullable(); // Arabic
            $table->string('name_ur')->nullable(); // Urdu

            $table->string('description_en')->nullable();
            $table->string('description_ar')->nullable();
            $table->string('description_ur')->nullable();

            $table->string('contact_email')->nullable();
            $table->string('phone')->nullable();

            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academies');
    }
};
