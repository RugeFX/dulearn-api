<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'material_id', 'title', 'body', 'created_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function material()
    {
        return $this->belongsTo('App\Models\Material');
    }

    public function replies()
    {
        return $this->hasMany('App\Models\Reply');
    }

    public function delete()
    {
        $this->replies()->delete();
        return parent::delete();
    }
}
