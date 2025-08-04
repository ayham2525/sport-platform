<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('system_id')->nullable();

            $table->string('action'); // e.g., "created_evaluation", "updated_program"
            $table->string('target_type')->nullable(); // polymorphic
            $table->unsignedBigInteger('target_id')->nullable();

            $table->text('message')->nullable(); // readable note: "Admin X deleted Program Y"
            $table->json('payload')->nullable(); // optional snapshot (before/after)

            $table->timestamps();

            // Foreign keys (optional for flexibility)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('system_id')->references('id')->on('systems')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
