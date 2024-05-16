<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupChatUserResource extends JsonResource
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
            'user' => UserResource::make($this->whenLoaded('user')),
            'permissions' => [
                'delete' => $this->when(auth()->check(), function () {
                    return auth()->user()->can('delete', $this->resource);
                }),
                'update' => $this->when(auth()->check(), function () {
                    return auth()->user()->can('update', $this->resource);
                }),
            ],
            'group_chat' => $this->whenLoaded('groupChat'),
            'joined_at' => $this->joined_at,
            'role' => $this->role
        ];
    }
}
