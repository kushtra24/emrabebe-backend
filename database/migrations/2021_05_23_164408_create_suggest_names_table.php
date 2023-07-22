<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggest_names', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('gender')->nullable();
            $table->string('meaning');
            $table->integer('origin_id')->nullable();
            $table->boolean('approved')->default(false);
            $table->boolean('exists')->nullable();
            $table->boolean('suggest_change')->default(false)->nullable();
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
        Schema::dropIfExists('suggest_names');
    }
}
