<?php

namespace App\Models;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_type',
        'ticket_code',
        'total_payment',
        'status',
        'event_id',
        'quantity'
    ];

    
        public function event()
        {
            return $this->belongsTo(Event::class, 'id', 'event_id');
        }
    
    
}
