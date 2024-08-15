<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersIntent extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'price',
        'type',
        'user_email',
        'user_phone',
        'expiration_date',
    ];
}



