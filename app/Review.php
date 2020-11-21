<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
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
