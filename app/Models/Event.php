<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'category',
        'title',
        'description',
        'date',
        'image',
        'city',
        'address',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'ticket_id');
    }

    public function typeTickets(): HasMany
    {
        return $this->hasMany(TicketsType::class, 'event_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'event_id');
    }

}
