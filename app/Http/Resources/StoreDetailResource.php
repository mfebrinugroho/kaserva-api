<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreDetailResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'banner_url' => $this->banner_url,
            'is_open_now' => $this->isOpenNow(),
            'today_operating_hour' => new StoreOperatingHourResource(
                $this->whenLoaded('todayOperatingHour')
            ),
            'menu_categories' => MenuCategoryResource::collection(
                $this->whenLoaded('menuCategories')
            ),
            'address' => $this->address,
        ];
    }
}
