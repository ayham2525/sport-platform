<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoachEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::create('coach_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluation_id');
            $table->unsignedBigInteger('coach_id');
            $table->enum('evaluator_type', ['admin', 'student']);
            $table->unsignedBigInteger('evaluator_id');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('evaluation_id')->references('id')->on('evaluations')->onDelete('cascade');
            $table->foreign('coach_id')->references('id')->on('users')->onDelete('cascade'); // assuming coaches are in users
            $table->foreign('evaluator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('coach_evaluations');
    }
}
