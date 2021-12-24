<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthor2ToRainlabBlogPosts extends Migration
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
            $table->string('author2')->nullable();
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
            $table->dropColumn('author2');
        });
    }
}
