<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'class_id', 'subject_id', 'user_id', 'title', 'material', 'created_at',
    ];

    public function kelas()
    {
        return $this->hasOne("App\Models\Kelas", 'id', 'class_id');
    }

    public function subject(){
        return $this->hasOne("App\Models\Subject", 'id', 'subject_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }
    public function koleksi()
    {
        return $this->hasMany('App\Models\Koleksi');
    }
    public function delete()
    {
        $this->posts()->delete();
        $this->koleksi()->delete();
        return parent::delete();
    }
}
