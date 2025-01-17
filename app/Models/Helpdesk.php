<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Helpdesk extends Model
{
    protected $table = 'helpdesk';
    
    protected $fillable = [
        'title',
        'ticket_id',
        'status',
        'conversation'
    ];

    protected $casts = [
        'conversation' => 'array'
    ];
}
