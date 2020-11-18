<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // Taskモデルへのリレーション
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
