<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsParentCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('parent_companies', function (Blueprint $table) {

            $table->string('Image_path');
            $table->string('Company_logo_path');
            $table->string('Address');
            $table->string('Latitude');
            $table->string('Longitude');
            $table->string('No_Of_Dealers');
            $table->string('Establishment_Year');


            
        });
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
