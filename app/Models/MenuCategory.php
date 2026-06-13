<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
