<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationResponsesTable extends Migration
{
    public function up()
    {
        Schema::create('evaluation_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_evaluation_id');
            $table->unsignedBigInteger('criteria_id');
            $table->text('value'); // Can be text, yes/no, or numeric rating
            $table->timestamps();

            $table->foreign('coach_evaluation_id')->references('id')->on('coach_evaluations')->onDelete('cascade');
            $table->foreign('criteria_id')->references('id')->on('evaluation_criteria')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_responses');
    }
}
