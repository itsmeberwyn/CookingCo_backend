<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    use HasFactory;
    // use SoftDeletes;

    protected $fillable = [
        'user_id',
        'message',
    ];
}
