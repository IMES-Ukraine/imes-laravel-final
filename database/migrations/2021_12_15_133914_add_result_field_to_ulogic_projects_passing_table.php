<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResultFieldToUlogicProjectsPassingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ulogic_projects_passing', function (Blueprint $table) {
            //
            $table->integer('result')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ulogic_projects_passing', function (Blueprint $table) {
            //
            $table->dropColumn('result');
        });
    }
}
