<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNationalitiesTable extends Migration
{
    public function up()
    {
        Schema::create('nationalities', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->string('iso_code', 3)->nullable(); // e.g. 'UAE', 'SYR'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nationalities');
    }
}
