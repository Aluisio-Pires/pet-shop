<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUUID, SoftDeletes;

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $fillable = [
        'category_uuid',
        'title',
        'price',
        'description',
        'metadata',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'uuid');
    }
}
