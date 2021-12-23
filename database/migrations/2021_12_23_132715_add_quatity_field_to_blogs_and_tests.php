<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuatityFieldToBlogsAndTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rainlab_blog_posts', function (Blueprint $table) {
            //
            $table->integer('amount')->default(0);
        });

        Schema::table('ulogic_tests_questions', function (Blueprint $table) {
            //
            $table->integer('amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rainlab_blog_posts', function (Blueprint $table) {
            //
            $table->dropColumn('amount');
        });

        Schema::table('ulogic_tests_questions', function (Blueprint $table) {
            //
            $table->dropColumn('amount');
        });
    }
}
