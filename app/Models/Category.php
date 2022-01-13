<?php

namespace App\Models;

use App\Traits\BaseModel;
use App\Traits\SyncMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory, Sluggable, InteractsWithMedia, SyncMedia, BaseModel;

    protected $fillable = [
        'id',
        'name',
        'description',
        'discount',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'section_id',
        'parent_id',
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

    public function setSectionIdAttribute($value)
    {
        $this->attributes['section_id'] = (int) $value;
    }
    public function setParentIdAttribute($value)
    {
        if (!$value = (int) $value) {
            $this->attributes['parent_id'] = null;
            return;
        }
        $this->attributes['parent_id'] = $value;
    }
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (int) $value;
    }

    public function section()
    {
        return $this->belongsTo(Section::class); // ->select('id', 'name'); // ->where('status', 1);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function childrens()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('childrens.photo');
    }

    public function products()
    {
        return $this->hasMany(Product::class); // ->where('status', 1);
    }

    public function photo()
    {
        return $this->morphOne(config('media-library.media_model'), 'model')->where('collection_name', 'photos');
    }

    public function scopeNull(Builder $query, string $columnsString): Builder
    {
        $columns = explode(',', $columnsString);
        foreach ($columns as $column) {
            $query = $query->whereNull($column);
        }
        return $query;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb-cropped')->crop('crop-center', 200, 200);
        // $this->addMediaConversion('thumb')->height(200)->sharpen(10);
    }
}






// public function products()
    // {
    //     return $this->belongsToMany(Product::class, 'products_categories')->withTimestamps();
    // }

    // public function allProducts()
    // {
    //     $allProducts = collect([]);
    //     $mainCategoryProducts = $this->products;
    //     $allProducts = $allProducts->concat($mainCategoryProducts);
    //     if ($this->children->isNotEmpty()) {
    //         foreach ($this->children as $child) {
    //             $allProducts = $allProducts->concat($child->products);
    //         }
    //     }
    //     return $allProducts;
    // }

    // public function scopeSearchName($query, $value)
    // {
    //     return $query->orwhere('name', 'like', "%$value%");
    // }
