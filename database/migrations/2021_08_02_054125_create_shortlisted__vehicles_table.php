<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortlistedVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shortlisted__vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            // $table->unsignedInteger('subsidiary_id');
            $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('subsidiary_id')->references('id')->on('subsidiaries');
            $table->timestamps();
            $table->softdeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shortlisted__vehicles');
    }
}
