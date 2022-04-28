<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'duration',
        'calories',
        'servings',
        'procedure',
        'ingredient',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
