<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendees extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'event_id'
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'id', 'event_id');
    }
}
