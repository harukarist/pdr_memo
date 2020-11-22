<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignToPreps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preps', function (Blueprint $table) {
            //外部キーの削除
            $table->dropForeign(['task_id']);
            // 外部キーをcascadeオプションありで設定する
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
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
            //外部キーの削除
            $table->dropForeign(['task_id']);
            // 外部キーの設定
            $table->foreign('task_id')->references('id')->on('tasks');
        });
    }
}
