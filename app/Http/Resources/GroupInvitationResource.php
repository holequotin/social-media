<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupInvitationResource extends JsonResource
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
            'inviter' => UserResource::make($this->whenLoaded('inviter')),
            'be_invite' => UserResource::make($this->whenLoaded('beInvite')),
            'group' => GroupResource::make($this->whenLoaded('group')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
