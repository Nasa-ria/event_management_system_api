<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventpromotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_name',
        'promotion_type',
        'discount',
    ] ;
        
}
