<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabyNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_names', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('gender_id');
            $table->text('description')->nullable();
            $table->string('meaning')->nullable();
            $table->string('meaning_de')->nullable();
            $table->string('meaning_al')->nullable();
            $table->integer('favored')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baby_names');
    }
}
