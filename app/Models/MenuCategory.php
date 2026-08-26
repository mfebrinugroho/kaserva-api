<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'sort_order',
        'is_active',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $query, string $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhereHas('store', function ($r) use ($search) {
                        $r->where('name', 'ILIKE', "%{$search}%");
                    });
            });
        });
    }
}
