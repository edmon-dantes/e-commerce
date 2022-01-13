<?php

namespace App\Models\Permission;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role;

class RoleSpatie extends Role
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'guard_name',
        'status',
        'slug',
    ];

    protected $attributes = [
        'guard_name' => 'api',
        'status' => false,
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (int) $value;
    }
}
