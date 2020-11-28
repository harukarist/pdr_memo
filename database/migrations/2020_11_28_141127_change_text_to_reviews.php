<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTextToReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('review_text')->nullable()->change();
            $table->text('good_text')->nullable()->change();
            $table->text('problem_text')->nullable()->change();
            $table->text('try_text')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('review_text')->nullable()->default('')->change();
            $table->string('good_text')->nullable()->default('')->change();
            $table->string('problem_text')->nullable()->default('')->change();
            $table->string('try_text')->nullable()->default('')->change();
        });
    }
}
