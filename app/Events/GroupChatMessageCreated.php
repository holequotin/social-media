<?php

namespace App\Events;

use App\Http\Resources\GroupChatMessageResource;
use App\Models\GroupChatMessage;
use App\Models\GroupChatUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupChatMessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $groupChatMessage;
    public $channels;

    /**
     * Create a new event instance.
     */
    public function __construct(GroupChatMessage $groupChatMessage)
    {
        $groupChatMessage->load(['user', 'groupChat']);
        $this->groupChatMessage = new GroupChatMessageResource($groupChatMessage);
        $this->getBroadcastOnChannels();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return $this->channels;
    }

    public function getBroadcastOnChannels()
    {
        $userIds = GroupChatUser::where('group_chat_id', $this->groupChatMessage->group_chat_id)
            ->whereNot('user_id', auth()->id())
            ->pluck('user_id');

        $channels = $userIds->map(function ($item) {
            return new PrivateChannel('Chat.Group.User.' . $item);
        });

        $this->channels = $channels->toArray();
    }
}
