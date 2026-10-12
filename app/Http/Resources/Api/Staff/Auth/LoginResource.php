<?php

namespace App\Http\Resources\Api\Staff\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
            'email' =>  $this->email,
            'role_id' => $this->role_id,
            'store_id' => $this->store_id,
        ];
    }
}
