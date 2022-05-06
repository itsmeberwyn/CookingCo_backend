<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reported_user extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $fillable = [
        'user_id',
        'reason',
    ];
}
