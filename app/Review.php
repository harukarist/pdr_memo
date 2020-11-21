<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // リレーション
    public function record()
    {
        return $this->belongsTo('App\Record');
    }
}
