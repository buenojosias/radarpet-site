<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVulnerablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vulnerables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('type', 20);
            $table->text('description');
            $table->enum('status', ['pending','whatched','canceled','done'])->defaul('pending');
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
        Schema::dropIfExists('vulnerables');
    }
}
