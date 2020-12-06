<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // ステータスの定義
    const CATEGORIES = [
        1 => ['id' => 1, 'category_name' => 'Input', 'category_class' => 'badge-light'],
        2 => ['id' => 2, 'category_name' => 'Output', 'category_class' => 'badge-light'],
        3 => ['id' => 3, 'category_name' => 'Etc', 'category_class' => 'badge-light'],
    ];

    // ロックをかけないカラム
    protected $fillable = ['project_name', 'project_color', 'project_target', 'category_id'];

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
        if (!isset(self::CATEGORIES[$category_id])) {
            return '';
        }

        // 配列からステータス値をキーに探索して値を返す
        return self::CATEGORIES[$category_id]['category_name'];
    }

    // ステータスに対応するクラスの返却
    public function getCategoryClassAttribute()
    {
        // テーブルのステータス値を代入
        $category_id = $this->attributes['category_id'];

        // 定義されていなければ空文字を返す
        if (!isset(self::CATEGORIES[$category_id])) {
            return '';
        }

        // 配列からステータス値をキーに探索して値を返す
        return self::CATEGORIES[$category_id]['category_class'];
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
