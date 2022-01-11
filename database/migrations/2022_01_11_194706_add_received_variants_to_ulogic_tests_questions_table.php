<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceivedVariantsToUlogicTestsQuestionsTable extends Migration
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
            $table->string('received_variants')->default('[]');
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
            $table->dropColumn('received_variants');
        });
    }
}
