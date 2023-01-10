<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisId extends Model
{
    use HasFactory;

    protected $table = 'registered_ids';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'reg_num', 'type', 'is_used', 'created_at',
    ];
}
