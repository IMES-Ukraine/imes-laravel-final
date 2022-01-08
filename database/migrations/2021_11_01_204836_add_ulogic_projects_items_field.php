<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUlogicProjectsItemsField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ulogic_projects_items', function (Blueprint $table) {
            $table->integer('item_key')->default(1)->after('project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ulogic_projects_items', function (Blueprint $table) {
            $table->dropColumn('item_key');
        });
    }
}
