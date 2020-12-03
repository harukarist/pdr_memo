<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prep extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // フォーム選択肢の定義
    const UNIT_TIME = ['5', '15', '25', '30', '45', '60'];
    const ESTIMATED_STEPS = ['1', '2', '3', '4', '5'];

    // リレーション先のレコードも論理削除
    protected static function boot()
    {
        parent::boot();
        self::deleting(function ($prep) {
            $prep->reviews()->delete();
        });
    }

    // ロックをかけないカラム
    protected $fillable = ['prep_text', 'unit_time', 'estimated_steps', 'category_id'];

    // 更新時に親の更新日時も更新
    // protected $touches = ['task'];

    // 日付のフォーマット
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("m/d H:i");
    }

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
