<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'reg_num', 'profile_picture', 'password', 'level_id', 'created_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function registeredUser(){
        return $this->belongsTo(RegisteredUser::class, "reg_num", "reg_num");
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function koleksi(){
        return $this->hasMany(Koleksi::class);
    }

    public static function boot(){
        parent::boot();
        self::deleting(function($user) {
            $user->materials->each(function($material) {
                $material->delete();
            });
            $user->posts->each(function($post) {
                $post->delete();
            });
            $user->replies->each(function($reply) {
                $reply->delete();
            });
        });
    }
}
