<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // ロックをかけないカラム
    protected $fillable = ['review_text', 'good_text', 'problem_text', 'try_text', 'flow_level', 'actual_time', 'category_id', 'started_at'];

    const FLOW_LEVEL = [
        1 => ['value' => 1, 'level_name' => '集中できなかった'],
        2 => ['value' => 2, 'level_name' => 'やや集中できなかった'],
        3 => ['value' => 3, 'level_name' => 'まあまあ'],
        4 => ['value' => 4, 'level_name' => '集中できた'],
        5 => ['value' => 5, 'level_name' => 'とても集中できた'],
    ];

    // 日時のフォーマット
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("m/d H:i");
    }
    public function getStartedAtAttribute($value)
    {
        return Carbon::parse($value)->format("m/d H:i");
    }
    public function getFinishedAtAttribute($value)
    {
        return Carbon::parse($value)->format("H:i");
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
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
