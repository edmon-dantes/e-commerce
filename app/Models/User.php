<?php

namespace App\Models;

use App\Traits\BaseModel;
use App\Traits\SyncMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, HasMedia
{
    use HasFactory, Notifiable, HasRoles, Sluggable, InteractsWithMedia, SyncMedia, BaseModel;

    protected static $relations_to_cascade = ['photo'];

    protected $guard_name = 'api';

    protected $fillable = [
        'id',
        'name',
        'lastname',
        'mothers_lastname',
        'username',
        'email',
        'phone_number',
        'password',
        'status',
        'slug',
        'fullname',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'username',
            ],
        ];
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (int) $value;
    }
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->setFullnameAttribute();
    }
    public function setFullnameAttribute()
    {
        $this->attributes['fullname'] = trim(join(' ', array($this->name, $this->last_name, $this->mothers_lastname)));
    }
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function photo()
    {
        return $this->morphOne(config('media-library.media_model'), 'model')->where('collection_name', 'photos');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'username' => $this->username,
                'fullname' => $this->fullname,
                'email' => $this->email,
                'slug' => $this->slug,
            ],
            'permissions' => $this->getAllPermissions()->pluck('name'),
        ];
    }
}
