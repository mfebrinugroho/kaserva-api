<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Builder;
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
        'banner',
        'address',
        'phone',
        'latitude',
        'longitude',
        'is_open',
        'closed_reason',
        'is_accept_order',
        'is_active',
    ];

    protected $appends = ['image_url', 'banner_url'];

    // public function getRouteKeyName(): string
    // {
    //     return 'slug';
    // }

    public function operatingHours()
    {
        return $this->hasMany(StoreOperatingHour::class);
    }

    public function todayOperatingHour()
    {
        return $this->hasOne(StoreOperatingHour::class)
            ->where('day_of_week', now()->dayOfWeek);
    }

    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_users');
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'store_users')
            ->whereHas('role', function ($query) {
                $query->where('slug', UserRole::Owner->value);
            });
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image
                ? asset('storage/' . $this->image)
                : asset('storage/images/stores/profile/default.jpg'),
        );
    }

    protected function bannerUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->banner
                ? asset('storage/' . $this->banner)
                : asset('storage/images/stores/banner/default.jpg'),
        );
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()

            ->generateSlugsFrom('name')

            ->saveSlugsTo('slug');
    }

    public function isOpenNow(): bool
    {
        if (!$this->is_open) {
            return false;
        }

        $todayHour = $this->todayOperatingHour;

        if (!$todayHour) {
            return false;
        }

        if (!$todayHour->is_open) {
            return false;
        }

        $now = now()->format('H:i:s');

        return $now >= $todayHour->open_time
            && $now <= $todayHour->close_time;
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $query, string $search) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('address', 'ILIKE', "%{$search}%");
            });
        });
    }
}
