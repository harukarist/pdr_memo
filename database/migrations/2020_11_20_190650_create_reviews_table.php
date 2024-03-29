<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('review_text')->nullable();
            $table->text('good_text')->nullable();
            $table->text('problem_text')->nullable();
            $table->text('try_text')->nullable();
            $table->integer('flow_level');
            $table->dateTime('started_at');
            $table->dateTime('finished_at')->nullable();
            $table->integer('actual_time');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('prep_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->timestamps();

            // 外部キーを設定
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('prep_id')->references('id')->on('preps')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onUpdate('cascade')->onDelete('restrict');

            // ソフトデリートを定義
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
