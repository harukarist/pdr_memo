<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

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
