<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory, HasUUID;

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $fillable = [
        'title',
    ];

    public function orders(){
        $this->hasMany(Order::class);
    }
}
