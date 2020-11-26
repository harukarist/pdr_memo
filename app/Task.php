<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // ステータスの定義
    const STATUS = [
        1 => ['status_name' => '未着手', 'status_class' => 'badge-danger'],
        2 => ['status_name' => '着手中', 'status_class' => 'badge-info'],
        3 => ['status_name' => '完了', 'status_class' => ''],
    ];

    // ロックをかけないカラム
    protected $fillable = ['task_name', 'project_id', 'due_date', 'status'];

    // リレーション先のレコードも論理削除
    protected static function boot()
    {
        parent::boot();
        self::deleting(function ($task) {
            $task->preps()->delete();
        });
    }

    // アクセサでモデルクラスのデータを加工する
    // ステータスの返却
    public function getStatusNameAttribute()
    {
        // テーブルのステータス値を代入
        $status = $this->attributes['status'];

        // 定義されていなければ空文字を返す
        if (!isset(self::STATUS[$status])) {
            return '';
        }

        // 配列からステータス値をキーに探索して値を返す
        return self::STATUS[$status]['status_name'];
    }

    // ステータスに対応するクラスの返却
    public function getStatusClassAttribute()
    {
        // テーブルのステータス値を代入
        $status = $this->attributes['status'];

        // 定義されていなければ空文字を返す
        if (!isset(self::STATUS[$status])) {
            return '';
        }

        // 配列からステータス値をキーに探索して値を返す
        return self::STATUS[$status]['status_class'];
    }

    // 日付のフォーマット
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("m/d H:i");
    }
    // 期限日の整形
    // public function getFormattedDueDateAttribute()
    // {
    //     return Carbon::createFromFormat('Y-m-d', $this->attributes['due_date'])
    //         ->format('Y/m/d');
    // }

    // リレーション
    public function project()
    {
        return $this->belongsTo('App\Project');
    }
    public function preps()
    {
        return $this->hasMany('App\Prep');
    }
}
