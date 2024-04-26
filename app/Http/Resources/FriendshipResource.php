<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendshipResource extends JsonResource
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
            'from_user' => UserResource::make($this->whenLoaded('fromUser')),
            'to_user' => UserResource::make($this->whenLoaded('toUser')),
            'status' => $this->status,
            'from_user_nickname' => $this->from_user_nickname,
            'to_user_nickname' => $this->to_user_nickname
        ];
    }
}
