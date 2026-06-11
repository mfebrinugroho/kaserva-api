<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreOperatingHour extends Model
{
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
