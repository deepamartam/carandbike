<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInOwnerVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('owner_vehicles', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->string('vehicle')->nullable()->after('id');
            $table->longText('description')->nullable()->after('vehicle');
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
        Schema::table('owner_vehicles', function (Blueprint $table) {
            $table->dropColumn('vehicle');
            $table->dropColumn('description');
        });
    }
}
