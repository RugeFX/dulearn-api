<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'class',
    ];
}
