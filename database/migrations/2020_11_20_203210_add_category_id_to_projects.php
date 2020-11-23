<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // カラム追加
            $table->bigInteger('category_id')->unsigned()->after('user_id');
            // 外部キーを設定
            $table->foreign('category_id')->references('id')->on('categories')
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
        Schema::table('projects', function (Blueprint $table) {
            // 外部キー制約を解除
            $table->dropForeign(['category_id']);
            // カラム削除
            $table->dropColumn('category_id');
        });
    }
}