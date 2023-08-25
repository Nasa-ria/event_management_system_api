<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Feedback;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'event',
        'date',
        'start_time',
        'end_time',
        'details',
          'status',
        'capacity',
        'venue',
        'date',
        
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'ticket_types_and_prices'
    ];

 
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_id', 'id');
    }

    public function attendeess()
    {
        return $this->belongsTo(Attendees::class, 'event_id', 'id');
    }
}
