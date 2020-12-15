<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncodingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encodings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('parent_id')->index();
            $table->tinyInteger('start_position');
            $table->tinyInteger('end_position');
            $table->string('match_value');
            $table->string('label');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('encodings');
    }
}
