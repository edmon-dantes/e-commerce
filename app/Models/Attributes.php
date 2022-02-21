<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attributes extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'id',
        'name',
        'description',
        'type',
        'order',
        'values',
        'status',
        'slug'
    ];

    protected $casts = [
        'values' => 'array'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (int) $value;
    }

    /* spatie querybuilder */
    public function scopeStatus(Builder $query, string $value): Builder
    {
        return $query->where('status', (int) $value);
    }

    /* Sluggable */
    public function sluggable(): array
    {
        return ['slug' => ['source' => 'name']];
    }
}
