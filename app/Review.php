<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // ロックをかけないカラム
    protected $fillable = ['review_text', 'good_text', 'problem_text', 'try_text', 'actual_time', 'step_counter', 'category_id', 'prep_id'];

    // 更新時に親の更新日時も更新
    protected $touches = ['prep'];

    // リレーション
    public function prep()
    {
        return $this->belongsTo('App\Prep');
    }
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
