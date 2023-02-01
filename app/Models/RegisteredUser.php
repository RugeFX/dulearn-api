<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredUser extends Model
{
    use HasFactory;

    protected $table = 'registered_users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'class_id', 'reg_num', 'phone_num', 'name', 'is_used', 'created_at',
    ];

    public function kelas(){
        return $this->belongsTo("App\Models\Kelas");
    }
}
