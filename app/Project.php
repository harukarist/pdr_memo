<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // ステータスの定義
    const CATEGORY = [
        1 => ['category_name' => 'Input', 'category_class' => 'badge-light'],
        2 => ['category_name' => 'Output', 'category_class' => 'badge-light'],
        3 => ['category_name' => 'Etc', 'category_class' => 'badge-light'],
    ];

    // ロックをかけないカラム
    protected $fillable = ['project_name', 'category_id'];

    // リレーション先のレコードも論理削除
    protected static function boot()
    {
        parent::boot();
        self::deleting(function ($project) {
            $project->tasks()->delete();
        });
    }

    // アクセサでモデルクラスのデータを加工する
    // ステータスの返却
    public function getCategoryNameAttribute()
    {
        // テーブルのステータス値を代入
        $category_id = $this->attributes['category_id'];

        // 定義されていなければ空文字を返す
        if (!isset(self::CATEGORY[$category_id])) {
            return '';
        }

        // 配列からステータス値をキーに探索して値を返す
        return self::CATEGORY[$category_id]['category_name'];
    }

    // ステータスに対応するクラスの返却
    public function getCategoryClassAttribute()
    {
        // テーブルのステータス値を代入
        $category_id = $this->attributes['category_id'];

        // 定義されていなければ空文字を返す
        if (!isset(self::CATEGORY[$category_id])) {
            return '';
        }

        // 配列からステータス値をキーに探索して値を返す
        return self::CATEGORY[$category_id]['category_class'];
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
}
