<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResetForeignToReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            //外部キーの削除
            $table->dropForeign(['prep_id']);
            // 外部キーをcascadeオプションありで設定する
            $table->foreign('prep_id')->references('id')->on('preps')
                ->onDelete('cascade')->onUpdate('cascade');
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
            //外部キーの削除
            $table->dropForeign(['prep_id']);
            // 外部キーの設定
            $table->foreign('prep_id')->references('id')->on('preps');
        });
    }
}
