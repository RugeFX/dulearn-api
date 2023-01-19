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
        'reg_num', 'profile_picture', 'password', 'level_id', 'created_at',
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
