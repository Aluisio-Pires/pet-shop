<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, HasUUID;

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $fillable = [
        'user_uuid',
        'order_status_uuid',
        'payment_uuid',
        'products',
        'address',
        'delivery_fee',
        'amount',
        'shipped_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'uuid');
    }
    public function orderStatus(){
        return $this->belongsTo(OrderStatus::class, 'uuid');
    }
    public function payment(){
        return $this->belongsTo(Payment::class, 'uuid');
    }
}
