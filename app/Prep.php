<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prep extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // リレーション先のレコードも論理削除
    protected static function boot()
    {
        parent::boot();
        self::deleting(function ($prep) {
            $prep->reviews()->delete();
        });
    }

    // ロックをかけないカラム
    protected $fillable = ['prep_text', 'unit_time', 'estimated_steps', 'category_id', 'task_id'];

    // リレーション
    public function task()
    {
        return $this->belongsTo('App\Task');
    }
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }
}
