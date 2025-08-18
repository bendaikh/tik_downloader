<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'session_id',
        'ip_address',
        'country_code',
        'country_name',
        'user_agent',
        'device_type',
        'browser',
    ];
}


