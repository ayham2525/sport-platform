<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_native')->nullable();
            $table->string('iso2', 2)->unique();
            $table->string('iso3', 3)->unique();
            $table->string('phone_code')->nullable(); 
            $table->string('currency')->nullable();
            $table->string('currency_symbol')->nullable(); 
            $table->string('flag')->nullable(); 
            $table->boolean('is_active')->default(true); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('countries');
    }
}

?>