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
        'email',
        'contact',
        'capacity',
        "venue",
        "date"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function User()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_id', 'id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'event_id', 'id');
    }

    public function attendeess()
    {
        return $this->belongsTo(Attendees::class, 'event_id', 'id');
    }
}
