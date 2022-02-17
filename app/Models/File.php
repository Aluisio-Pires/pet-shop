<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory, HasUUID;

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $fillable = [
        'name',
        'path',
        'size',
        'type',
    ];
}
