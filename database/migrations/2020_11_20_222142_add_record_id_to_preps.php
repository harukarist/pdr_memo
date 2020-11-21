<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecordIdToPreps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preps', function (Blueprint $table) {
            // カラム追加
            $table->bigInteger('record_id')->unsigned();
            // 外部キーを設定
            $table->foreign('record_id')->references('id')->on('records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preps', function (Blueprint $table) {
            // 外部キー制約を解除
            $table->dropForeign(['record_id']);
            // カラム削除
            $table->dropColumn('record_id');
        });
    }
}
