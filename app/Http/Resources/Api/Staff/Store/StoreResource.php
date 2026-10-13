<?php

namespace App\Http\Resources\Api\Staff\Store;

use App\Enums\UserRole;
use App\Http\Resources\Api\Staff\Menu\MenuResource;
use App\Http\Resources\Api\Staff\MenuCategory\MenuCategoryResource;
use App\Http\Resources\Api\Staff\User\UserResource;
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
        $user = $request->user();
        $userRole = $user->hasRole(UserRole::SuperAdmin);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'banner_url' => $this->banner_url,
            'address' => $this->address,
            'phone' => $this->phone,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_open' => $this->is_open,
            'closed_reason' => $this->closed_reason,
            'is_accept_order' => $this->is_accept_order,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'is_open_now' => $this->isOpenNow(),
            // 'today_operating_hour' => new StoreOperatingHourResource(
            //     $this->whenLoaded('todayOperatingHour')
            // ),
            'menu_categories' => MenuCategoryResource::collection(
                $this->whenLoaded('menuCaregories')
            ),
            'menus' => MenuResource::collection(
                $this->whenLoaded('menus')
            ),
            'users' => MenuResource::collection(
                $this->whenLoaded('users')
            ),
            'owner' => $this->when(
                $userRole,
                fn() => new UserResource($this->owner->first())
            ),
        ];
    }
}
