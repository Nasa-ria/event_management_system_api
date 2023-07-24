<?php

namespace App\Models;

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
        'attendees',
        "user_id",
        "location",
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
        return $this->belongsTo(User::class,'id','user_id');
    }
    public function Ticket()
    {
        return $this->belongsTo(Ticket::class,'id','event_id');
    }
}
