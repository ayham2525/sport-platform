<?php
// database/migrations/xxxx_xx_xx_create_branch_item_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchItemTable extends Migration
{
    public function up()
    {
        Schema::create('branch_item', function (Blueprint $table) {
            $table->id();

            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');

            $table->decimal('min_value', 8, 2)->nullable();
            $table->decimal('max_value', 8, 2)->nullable();
            $table->text('notes')->nullable(); // optional: for professional info
            $table->boolean('is_professional')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('branch_item');
    }
}
