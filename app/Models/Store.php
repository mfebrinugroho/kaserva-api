<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Store extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'address',
        'phone',
        'is_open',
    ];

    protected $appends = ['image_url'];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn($image) => $this->image ? asset('storage/' . $this->image) : null,
        );
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()

            ->generateSlugsFrom('name')

            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
