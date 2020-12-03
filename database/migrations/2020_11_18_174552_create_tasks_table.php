<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('task_name');
            $table->date('due_date')->nullable();
            $table->integer('status')->default(1);
            $table->integer('priority')->default(0);
            $table->integer('done_count')->default(0);
            $table->bigInteger('project_id')->unsigned();
            $table->timestamps();
            
            // 外部キーをcascadeオプションありで設定する
            $table->foreign('project_id')->references('id')->on('projects')
            ->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('tasks');
    }
}
