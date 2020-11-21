<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    // リレーション
    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function preps()
    {
        return $this->hasMany('App\Prep');
    }
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }
}
