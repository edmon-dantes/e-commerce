<?php

namespace App\Models;

use App\Traits\BaseModel;
use App\Traits\Helpers;
use App\Traits\SyncMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, Sluggable, InteractsWithMedia, SyncMedia, BaseModel, helpers;

    protected $guard_name = 'api';

    protected $fillable = [
        'name',
        'sku',
        'description',
        'details',
        'price',
        'stock',
        'discount',
        'fabric',
        'pattern',
        'sleeve',
        'fit',
        'occassion',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_featured',
        'category_id',
        'brand_id',
        'status',
    ];

    protected $cast = [
        // 'properties' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'fullname',
            ],
        ];
    }

    public function setIsFeaturedAttribute($value)
    {
        $this->attributes['is_featured'] = (int) $value;
    }

    public function setCategoryIdAttribute($value)
    {
        $this->attributes['category_id'] = (int) $value;
    }

    public function setBrandIdAttribute($value)
    {
        $this->attributes['brand_id'] = (int) $value;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (int) $value;
    }

    public function setFullnameAttribute()
    {
        $this->attributes['fullname'] = trim(join(' ', array($this->name, $this->sku)));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->setFullnameAttribute();
    }

    public function setSkuAttribute($value)
    {
        $this->attributes['sku'] = $value;
        $this->setFullnameAttribute();
    }

    public function category()
    {
        return $this->belongsTo(Category::class); // ->select('id', 'name'); // ->where('status', 1);
    }

    // public function attributes()
    // {
    //     return $this->hasMany(ProductsAttribute::class); // ->where('status', 1);
    // }

    public function brand()
    {
        return $this->belongsTo(Brand::class); // ->select('id', 'name'); // ->where('status', 1);
    }

    public function pictures()
    {
        return $this->morphMany(config('media-library.media_model'), 'model')->where('collection_name', 'pictures');
    }

    public function scopeSection(Builder $query, ...$value): Builder
    {
        $query->whereHas('section', function (Builder $query) use ($value) {
            $query->whereIn('slug', $value);
        });

        return $query;
    }

    public function scopeCategory(Builder $query, ...$value): Builder
    {
        $categories = Category::whereIn('slug', $value)->with('children')->get()->toArray();

        $categories_slug = array_column($this->flatten($categories), 'slug');

        $query->whereHas('category', function (Builder $query) use ($categories_slug) {
            $query->whereIn('slug', $categories_slug);
        });

        return $query;
    }

    public function scopeBrand(Builder $query, ...$value): Builder
    {
        $query->whereHas('brand', function (Builder $query) use ($value) {
            $query->whereIn('slug', $value);
        });

        return $query;
    }

    public function scopeIsFeatured(Builder $query, string $value): Builder
    {
        return $query->where('is_featured', (int) $value);
    }

    public function scopeSearch(Builder $query, string $value): Builder
    {
        return $query->where('fullname', 'like', '%' . $value . '%')->orWhere('sku', 'like', '%' . $value . '%');
    }
    public function scopeStatus(Builder $query, string $value): Builder
    {
        return $query->where('status', (int) $value);
    }

    public function registerMediaCollections(): void
    {
        // const PHOTO_SIZES = [[425, 271], [500, 500], [850, 500], [1000, 591], [1294, 500]];

        // $this->addMediaCollection('pictures')->withResponsiveImages();

        $this->addMediaCollection('pictures')->registerMediaConversions(function (Media $media) {
            // $this->addMediaConversion('thumb')
            //     ->width(200)
            //     ->height(200)
            //     ->sharpen(10);

            $this->addMediaConversion('thumb-cropped')->crop('crop-center', 180, 180); // Trim or crop the image to the center for sepecified width and height.

            // $this->addMediaConversion('large-crop')->crop('crop-center', 1000, 291);
            // $this->addMediaConversion('medium-crop')->crop('crop-center', 500, 500);
            // $this->addMediaConversion('small-crop')->crop('crop-center', 200, 200);
        });
    }

    // public function registerMediaConversions(Media $media = null): void
    // {
    //     $this->addMediaConversion('thumb')
    //         ->width(200)
    //         ->height(200)
    //         ->sharpen(10);
    // }
}












// public function setActiveAttribute($value)
    // {
    //     $this->attributes['active'] = (int) (bool) $value;
    // }
    // public function setSlugAttribute($value)
    // {
    //     $this->attributes['slug'] = Str::slug($value, '-');
    // }
    // public function setPropertiesAttribute($value)
    // {
    //     $this->attributes['properties'] = is_array($value) ? json_encode($value) : null;
    // }

    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class, 'products_categories')->withTimestamps();
    // }
    // public function shop()
    // {
    //     return $this->belongsTo(Shop::class);
    // }
    // public function orders()
    // {
    //     return $this->belongsToMany(Order::class, 'order_items', 'product_id', 'order_id')->withTimestamps()->withPivot('name', 'price', 'quantity');
    // }
    // public function images()
    // {
    //     return $this->morphMany(File::class, 'fileable')->where('format', 'image');
    // }
    // public function image()
    // {
    //     return $this->morphOne(File::class, 'fileable')->where('format', 'image')->latest();
    // }

    // public function scopeSearchName($query, $value)
    // {
    //     return $query->orwhere("name", "like", "%$value%");
    // }

    // public function save(array $options = array())
    // {
    //     $this->fullname = trim(join(' ', array($this->name, $this->code)));
    //     parent::save($options);
    // }
