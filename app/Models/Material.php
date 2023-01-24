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
        return $this->hasOne("App\Models\Kelas");
    }

    public function subject(){
        return $this->hasOne("App\Models\Subject");
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}