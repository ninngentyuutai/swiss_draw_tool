<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('tournament_id');
            $table->string('battle_record')->nullable();
            $table->string('option_keys')->nullable();
            $table->string('option_vals')->nullable();
            $table->integer('point')->nullable();
            $table->integer('os/m')->nullable();
            $table->integer('dos/m')->nullable();
            $table->integer('md/m')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('participants');
    }
};
