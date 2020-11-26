<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ResetForeignToPreps extends Migration
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
            $table->foreign('task_id')->references('id')->on('tasks')
                ->onUpdate('cascade')->onDelete('restrict');
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
