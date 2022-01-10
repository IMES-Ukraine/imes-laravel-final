<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePostCoverImageToCoverImageId extends Migration
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
            $table->renameColumn('cover_image', 'cover_image_id');
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
            $table->renameColumn('cover_image_id', 'cover_image');
        });
    }
}
