<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // Taskモデルへのリレーション
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
