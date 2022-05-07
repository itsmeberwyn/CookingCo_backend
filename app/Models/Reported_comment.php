<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reported_comment extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $fillable = [
        'user_id',
        'comment_id',
        'reason',
        'reported_by',
        'noreports',
    ];
}
