<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangePostScheduledToDatetime extends Migration
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
            $table->dropColumn('scheduled');
        });
        Schema::table('rainlab_blog_posts', function (Blueprint $table) {
            //
            $table->dateTime('scheduled')->default(DB::raw('CURRENT_TIMESTAMP'));
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
            $table->dropColumn('scheduled');
        });
        Schema::table('rainlab_blog_posts', function (Blueprint $table) {
            //
            $table->integer('scheduled')->default(0);
        });
    }
}
