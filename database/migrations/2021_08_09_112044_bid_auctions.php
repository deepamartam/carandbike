<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BidAuctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        /*Schema::create('bid_auctions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('ad_vehicle_id');
            $table->unsignedBigInteger('subsidiary_id');

            $table->unsignedInteger('offer');
            $table->tinyInteger('status')->default(0)->comment = '0 Pending, 1 Active, 2 Rejected';
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('subsidiary_id')->references('id')->on('subsidiaries');
            $table->foreign('ad_vehicle_id')->references('id')->on('ad_vehicles');

            $table->timestamps();
            $table->softDeletes();

          
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
