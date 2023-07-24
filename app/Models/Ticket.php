<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_name',
        'email',
        // 'status',
        // 'uniquecode',
        'event_id'
    ];
    public function Event()
    {
        $event= $this->hasMany(Event::class,'event_id','id');

        return $event;
    }
}
