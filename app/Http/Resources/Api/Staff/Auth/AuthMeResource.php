<?php

namespace App\Http\Resources\Api\Staff\Auth;

use App\Http\Resources\Api\Staff\Permission\PermissionResource;
use App\Http\Resources\Api\Staff\Role\RoleResource;
use App\Http\Resources\Api\Staff\Store\StoreMeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthMeResource extends JsonResource
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
            'email' => $this->email,
            'store_id' => $this->store_id,
            'role' => new RoleResource(
                $this->whenLoaded('role')
            ),
            'permissions' => PermissionResource::collection(
                $this->whenLoaded('role')?->permissions ?? collect()
            ),
            'stores' => StoreMeResource::collection(
                $this->whenLoaded('stores') ?? collect()
            ),
        ];
    }
}
