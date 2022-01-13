<?php

namespace App\Models;

use App\Traits\BaseModel;
use App\Traits\SyncMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Banner extends Model implements HasMedia
{
    use HasFactory, Sluggable, InteractsWithMedia, SyncMedia, BaseModel;

    protected $guard_name = 'api';

    protected $fillable = [
        'name',
        'description',
        'link',
        'status',
        'slug',
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

    public function photo()
    {
        return $this->morphOne(config('media-library.media_model'), 'model')->where('collection_name', 'photos')->latest();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb-cropped')->crop('crop-center', 200, 200);
        // $this->addMediaConversion('thumb')->height(200)->sharpen(10);
    }
}
