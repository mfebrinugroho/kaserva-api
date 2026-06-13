<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(MenuCategory::class);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image
                ? asset('storage/' . $this->image)
                : asset('storage/images/menus/menu-default.jpg'),
        );
    }
}
