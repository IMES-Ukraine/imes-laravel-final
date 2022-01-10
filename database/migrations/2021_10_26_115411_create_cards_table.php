<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('cards', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50);
                $table->integer('cost');
                $table->text('short_description', 150);
                $table->text('description', 2000)->nullable();
                $table->string('cover')->nullable();
                $table->unsignedBigInteger('category_id')->nullable();

                $table->boolean('is_active')->default(true);

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
        Schema::dropIfExists('cards');
    }
}
