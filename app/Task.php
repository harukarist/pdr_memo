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
        // 2 => ['status_name' => 'Prep済み', 'status_class' => 'badge-info'],
        3 => ['status_name' => '着手中', 'status_class' => 'badge-info'],
        4 => ['status_name' => '完了', 'status_class' => ''],
    ];

    // 優先度の定義
    const PRIORITY = [
        0 => ['priority_name' => 'なし', 'priority_class' => 'text-secondary'],
        1 => ['priority_name' => '★', 'priority_class' => 'text-info'],
        2 => ['priority_name' => '★★', 'priority_class' => 'text-success'],
        3 => ['priority_name' => '★★★', 'priority_class' => 'text-danger'],
    ];

    // ロックをかけないカラム
    protected $fillable = ['task_name', 'due_date', 'priority'];

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

    // 優先度
    public function getPriorityNameAttribute()
    {
        // テーブルのステータス値を代入
        $priority = $this->attributes['priority'];

        // 定義されていなければ空文字を返す
        if (!isset(self::PRIORITY[$priority])) {
            return '';
        }

        // 配列からステータス値をキーに探索して値を返す
        return self::PRIORITY[$priority]['priority_name'];
    }

    // ステータスに対応するクラスの返却
    public function getPriorityClassAttribute()
    {
        // テーブルのステータス値を代入
        $priority = $this->attributes['priority'];

        // 定義されていなければ空文字を返す
        if (!isset(self::PRIORITY[$priority])) {
            return '';
        }

        // 配列からステータス値をキーに探索して値を返す
        return self::PRIORITY[$priority]['priority_class'];
    }

    // 日付のフォーマット
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("m/d H:i");
    }
    // 期限日の整形
    // public function getFormattedDueDateAttribute()
    // {
    //     if ($this->attributes['due_date']) {
    //         setlocale(LC_ALL, 'ja_JP.UTF-8');
    //         return Carbon::createFromFormat('Y-m-d', $this->attributes['due_date'])
    //             ->formatLocalized('%m/%d(%a)');
    //     }
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
    public function reviews()
    {
        return $this->hasManyThrough('App\Review', 'App\Prep');
    }
}
