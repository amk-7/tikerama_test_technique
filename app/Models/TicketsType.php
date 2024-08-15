<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketsType extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'price',
        'quantity',
        'real_quantity',
        'total_quantity',
        'description',
        'event_id',
    ];

    public function increaseQuantity(int $_quantity){
        $this->quantity += $_quantity;
        $this->save();
    }

    public function reduceQuantity(int $_quantity){
        $this->quantity -= $_quantity;
        $this->save();
    }


    public function increaseRealQuantity(int $_quantity){
        $this->real_quantity += $_quantity;
        $this->save();
    }

    public function reduceRealQuantity(int $_quantity){
        $this->real_quantity -= $_quantity;
        $this->save();
    }


    public function increaseTotalQuantity(int $_quantity){
        $this->total_quantity += $_quantity;
        $this->save();
    }

    public function reduceTotalQuantity(int $_quantity){
        $this->total_quantity -= $_quantity;
        $this->save();
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
