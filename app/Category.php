<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;
}
