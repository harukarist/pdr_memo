<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // ロックをかけないカラム
    protected $fillable = ['project_name', 'project_color', 'project_target', 'category_id'];

    // リレーション先のレコードも論理削除
    protected static function boot()
    {
        parent::boot();
        self::deleting(function ($project) {
            $project->tasks()->delete();
        });
    }


    // リレーション
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
