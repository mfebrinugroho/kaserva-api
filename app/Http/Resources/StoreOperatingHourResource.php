<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreOperatingHourResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'day_of_week' => $this->day_of_week,
            'open_time' => Carbon::parse($this->open_time)->format('H:i'),
            'close_time' => Carbon::parse($this->close_time)->format('H:i'),
        ];
    }
}
