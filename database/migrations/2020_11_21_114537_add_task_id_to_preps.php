<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaskIdToPreps extends Migration
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
            $table->bigInteger('task_id')->unsigned()->after('id');
            // 外部キーを設定
            $table->foreign('task_id')->references('id')->on('tasks');
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
            $table->dropForeign(['task_id']);
            // カラム削除
            $table->dropColumn('task_id');
        });
    }
}
