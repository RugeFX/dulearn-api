<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'reg_num', 'profile_picture', 'name', 'password', 'level_id', 'created_at',
    ];

    public function level()
    {
        return $this->hasOne('App\Models\Level');
    }

    public function materials()
    {
        return $this->hasMany('App\Models\Material');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function replies()
    {
        return $this->hasMany('App\Models\Reply');
    }
}
