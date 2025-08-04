<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationCriteriaTable extends Migration
{
    public function up()
    {
        Schema::create('evaluation_criteria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluation_id');
            $table->string('label');
            $table->enum('input_type', ['rating', 'text', 'yesno']);
            $table->integer('weight')->default(1); // used in score calculation if needed
            $table->integer('order')->default(0);  // for display ordering
            $table->boolean('required')->default(true);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('evaluation_id')->references('id')->on('evaluations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_criteria');
    }
}
