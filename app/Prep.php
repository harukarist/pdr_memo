<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prep extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // ロックをかけないカラム
    // protected $fillable = ['prep_text', 'unit_time', 'estimated_steps', 'category_id'];

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
