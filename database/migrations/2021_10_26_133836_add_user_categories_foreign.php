<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserCategoriesForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_categories', function (Blueprint $table) {
            $table->foreign('category_id' )->references('id')->on('categories')->onUpdate('cascade');
            $table->foreign('user_id' )->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_categories', function (Blueprint $table) {
            $table->dropForeign('user_categories_category_id_foreign' );
            $table->dropForeign('user_categories_user_id_foreign' );
        });
    }
}
