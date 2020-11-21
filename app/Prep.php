<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prep extends Model
{
    // ロックをかけないカラム
    protected $fillable = ['prep_text', 'unit_time','estimated_steps','category_id'];

    // リレーション
    public function record()
    {
        return $this->belongsTo('App\Record');
    }
    // リレーション
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
