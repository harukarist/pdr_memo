<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrepIdToReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // カラム追加
            $table->bigInteger('prep_id')->unsigned();
            // 外部キーを設定
            $table->foreign('prep_id')->references('id')->on('preps');
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
            // 外部キー制約を解除
            $table->dropForeign(['prep_id']);
            // カラム削除
            $table->dropColumn('prep_id');
        });
    }
}