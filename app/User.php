<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projects()
    {
        return $this->hasMany('App\Project');
    }
    public function tasks()
    {
        return $this->hasManyThrough('App\Task', 'App\Project');
    }
    public function preps()
    {
        return $this->hasMany('App\Prep');
    }
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }
    public function categories()
    {
        return $this->hasMany('App\Category');
    }
}
