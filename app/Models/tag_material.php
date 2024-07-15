<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tag_material extends Model
{
    use HasFactory;

    protected $fillable = [
        'approved_materials_id',
        'tags_id'
    ];
}
