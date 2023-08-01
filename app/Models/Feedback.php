<?php

namespace App\Models;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'feedback',
    ];
    public function event()
    {
        return $this->belongsTo(Event::class, 'id', 'event_id');
    }

}
