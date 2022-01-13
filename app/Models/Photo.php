<?php

namespace App\Models;

use App\Traits\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use HasFactory, BaseModel;

    protected $fillable = [
        'name',
        'title',
        'description',
        'url',
        'properties',
        'status',
    ];

    protected $cast = [
        'properties' => 'array',
    ];

    public function setPropertiesAttribute($value)
    {
        $this->attributes['properties'] = is_array($value) ? json_encode($value) : null;
    }

    public function photoable()
    {
        return $this->morphTo();
    }
}
