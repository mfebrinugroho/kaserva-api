<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use Illuminate\Database\Eloquent\Model;

class StoreOperatingHour extends Model
{
    protected $fillable = [
        'store_id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_open',
    ];

    protected $casts = [
        'day_of_week' => DayOfWeek::class,
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
