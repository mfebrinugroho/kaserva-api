<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'address' => $this->address,
            'phone' => $this->phone,
            'is_open' => $this->is_open,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
