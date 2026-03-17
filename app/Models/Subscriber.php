<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'phone_opt_in',
        'opted_in_at',
        'opt_out_token',
        'last_sent_forecast',
        'last_sent_at',
        'sms_frequency',
    ];

    protected $casts = [
        'phone_opt_in' => 'boolean',
        'opted_in_at' => 'datetime',
        'last_sent_forecast' => 'array',
        'last_sent_at' => 'datetime',
    ];
}
