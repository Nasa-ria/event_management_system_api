<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'event_name',
        'email',
        'password',
        'contact',
        'attendees'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

}
