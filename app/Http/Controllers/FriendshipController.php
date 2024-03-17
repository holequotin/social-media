<?php

namespace App\Http\Controllers;

use App\Http\Requests\Friendship\SendFriendRequest;
use App\Http\Resources\FriendshipResource;
use App\Http\Resources\UserResource;
use App\Models\Friendship;
use App\Models\User;
use App\Services\FriendshipService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FriendshipController extends BaseApiController
{
    //
    public function __construct(protected FriendshipService $friendshipService) {
    }

    public function getFriendsByUser(Request $request, User $user)
    {
        $perPage = $request->perPage;
        $friends = $this->friendshipService->getFriendsByUser($user)->paginate($perPage);
        return $this->sendResponse([UserResource::collection($friends)]);
    }

    public function sendFriendRequest(SendFriendRequest $request)
    {
        $validated = $request->validated();
        $friendship = $this->friendshipService->storeFriendRequest($validated);

        return $this->sendResponse([FriendshipResource::make($friendship)],Response::HTTP_CREATED);
    }

    public function acceptFriendRequest(Request $request, Friendship $friendship)
    {
        $this->authorize('accept',$friendship);
        $friendship = $this->friendshipService->acceptFriendRequest($friendship);
    
        return $this->sendResponse(['friendship' => FriendshipResource::make($friendship)]);
    }
}
