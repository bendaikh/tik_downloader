<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'session_id',
        'ip_address',
        'user_agent',
        'video_id',
        'type',
        'bytes',
    ];
}


