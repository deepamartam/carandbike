<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('created_at');
        });
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('house_no')->nullable()->after('from_user_id');
            $table->string('street')->nullable()->after('house_no');
            $table->string('zip_code')->nullable()->after('street');
            $table->string('city')->nullable()->after('zip_code');
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
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('house_no');
            $table->dropColumn('street');
            $table->dropColumn('zip_code');
            $table->dropColumn('city');
        });
    }
}
