<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;


    // ロックをかけないカラム
    protected $fillable = ['category_name'];

    // リレーション
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function projects()
    {
        return $this->hasMany('App\Project');
    }
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }

    public static function getUsersCategories()
    {
        $categories = Auth::user()->categories;
        if ($categories) {
            foreach ($categories as $category) {
                $users_categories[$category->id]['category_name'] = $category->category_name;
            }
        } else {
            $user_categories = '';
        }
        return $users_categories;
    }
}
