<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // ステータスの定義
    const STATUS = [
        1 => ['name' => '未着手', 'class' => 'badge-danger'],
        2 => ['name' => '着手中', 'class' => 'badge-info'],
        3 => ['name' => '完了', 'class' => ''],
    ];

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
        return self::STATUS[$status]['name'];
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
        return self::STATUS[$status]['class'];
    }

    // 期限日の整形
    // public function getFormattedDueDateAttribute()
    // {
    //     return Carbon::createFromFormat('Y-m-d', $this->attributes['due_date'])
    //         ->format('Y/m/d');
    // }
}
