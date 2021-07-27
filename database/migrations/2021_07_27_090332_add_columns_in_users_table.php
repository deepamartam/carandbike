<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->integer('role_id')->unsigned()->after('password');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('phone')->nullable()->after('role_id');
            $table->tinyInteger('is_active')->default(1)->after('remember_token')->comment = '0 Inactive, 1 Active';
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id');
            $table->dropColumn('phone');
            $table->dropColumn('is_active');
        });
    }
}
