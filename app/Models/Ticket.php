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
        'price',
        'date',
        'status',
        'event_id'
    ];

    
        public function event()
        {
            return $this->belongsTo(Event::class, 'id', 'event_id');
        }
    
    
}
