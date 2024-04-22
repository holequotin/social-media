<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'type' => $this->type,
            'images' => PostImageResource::collection($this->whenLoaded('images')),
            'total_reaction' => $this->reactions()->paginate()->total(),
            'current_reaction' => ReactionResource::make($this->reactions()->where('user_id', auth()->id())->first()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => UserResource::make($this->whenLoaded('user')),
            'shared_post' => PostResource::make($this->whenLoaded('sharedPost')),
            'shared_post_id' => $this->shared_post_id,
            'group' => GroupResource::make($this->whenLoaded('group')),
        ];
    }
}
