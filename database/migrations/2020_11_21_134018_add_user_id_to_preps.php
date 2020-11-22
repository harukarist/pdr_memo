<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToPreps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preps', function (Blueprint $table) {
            // ユーザーIDカラムを追加
            $table->bigInteger('user_id')->unsigned();

            // 外部キーを設定する
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
            $table->dropForeign(['user_id']);
            // ユーザーIDカラムを削除
            $table->dropColumn('user_id');
        });
    }
}
