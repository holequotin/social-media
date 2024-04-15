<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'body' => $this->body,
            'from_user' => UserResource::make($this->whenLoaded('fromUser')),
            'to_user' => UserResource::make($this->whenLoaded('toUser')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
