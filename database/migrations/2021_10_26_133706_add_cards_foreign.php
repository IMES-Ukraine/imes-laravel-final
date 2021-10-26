<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCardsForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('cards', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade');
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
        Schema::table('cards', function (Blueprint $table) {
            $table->dropForeign('cards_category_id_foreign');
        });
    }
}
