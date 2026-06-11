<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Role extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug'
    ];

    protected $casts = [
        'role' => UserRole::class,
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()

            ->generateSlugsFrom('name')

            ->saveSlugsTo('slug');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
