<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_companies', function (Blueprint $table) {
      
                $table->increments('id');
                $table->string('company_name');
                $table->string('contact_person');
                $table->unsignedInteger('user_id')->nullable();
                // $table->unsignedInteger('subsidiary_id');
              
                $table->timestamps();
                $table->softdeletes();
                $table->foreign('user_id')->references('id')->on('users');
                // $table->foreign('subsidiary_id')->references('id')->on('subsidiaries');





              });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parent_companies');
    }
}
