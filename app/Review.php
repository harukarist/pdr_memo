<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // ステータスの定義
    // const CATEGORY = [
    //     1 => ['category_name' => 'Input', 'category_class' => 'badge-primary'],
    //     2 => ['category_name' => 'Output', 'category_class' => 'badge-success'],
    //     3 => ['category_name' => 'Etc', 'category_class' => 'badge-secondary'],
    // ];

    // ロックをかけないカラム
    protected $fillable = ['review_text', 'good_text', 'problem_text', 'try_text', 'actual_time', 'step_counter', 'category_id'];

    // 日付のフォーマット
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("m/d H:i");
    }

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
