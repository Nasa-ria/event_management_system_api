<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPrice extends Model
{
    use HasFactory;
    // include a $casts property that specifies the ticket_types_and_prices attribute as a JSON array:
    protected $casts = [
        'ticket_types_and_prices' => 'array',
    ];
}
