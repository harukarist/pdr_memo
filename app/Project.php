<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // リレーション先のレコードも論理削除
    protected static function boot()
    {
        parent::boot();
        self::deleting(function ($project) {
            $project->tasks()->delete();
        });
    }

    // Taskモデルへのリレーション
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
