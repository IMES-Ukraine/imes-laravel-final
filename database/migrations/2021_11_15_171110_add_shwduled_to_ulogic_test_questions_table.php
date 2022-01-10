<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddShwduledToUlogicTestQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ulogic_tests_questions', function (Blueprint $table) {
            //
            $table->dateTime('schedule')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('can_retake')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ulogic_tests_questions', function (Blueprint $table) {
            //
            $table->dropColumn('schedule');
            $table->dropColumn('can_retake');
        });
    }
}
