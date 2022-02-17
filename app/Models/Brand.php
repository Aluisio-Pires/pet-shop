<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, HasUUID;

    public function getRouteKeyName()
    {
        return 'uuid';
    }


    protected $fillable = [
        'title',
        'slug',
    ];
}
