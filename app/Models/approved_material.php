<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class approved_material extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'path',
        'original_path',
        'format',
        'type',
        'likes'
    ];
}
