<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specie_id')->constrained();
            $table->foreignId('race_id')->constrained()->nullable();
            $table->morphs('petable');
            $table->string('name', 30)->nullable();
            $table->enum('gender', ['m', 'f'])->nullable();
            $table->smallInteger('age')->nullable();
            $table->string('size', 20);
            $table->string('primary_color', 20);
            $table->string('secondary_color', 20)->nullable();
            $table->text('physical_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pets');
    }
}
