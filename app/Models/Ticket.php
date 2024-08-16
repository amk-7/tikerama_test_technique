<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'email',
        'phone',
        'price',
        'key',
        'status',
        'event_id',
        'type_id',
        'order_id',
        'created_on',
    ];

    protected static function booted(): void
    {
        static::created(function (Ticket $ticket) {
            $ticket->ticketType->reduceRealQuantity(1);
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'ticket_id');
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketsType::class, 'type_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

}
