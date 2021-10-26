<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserCardsForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_cards', function (Blueprint $table) {
            $table->foreign('card_id' )->references('id')->on('cards')->onUpdate('cascade');
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
        Schema::table('user_cards', function (Blueprint $table) {
            $table->dropForeign('user_cards_card_id_foreign' );
            $table->dropForeign('user_cards_user_id_foreign' );
        });
    }
}
