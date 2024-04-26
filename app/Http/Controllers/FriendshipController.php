<?php

namespace App\Http\Controllers;

use App\Http\Requests\Friendship\SendFriendRequest;
use App\Http\Requests\Friendship\UnfriendRequest;
use App\Http\Resources\FriendResource;
use App\Http\Resources\FriendshipResource;
use App\Http\Resources\SuggestionFriendResource;
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
        $friends = $this->friendshipService->getFriendsByUser($user, $request->perPage);
        return $this->sendPaginateResponse(FriendResource::collection($friends));
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

    public function unfriend(UnfriendRequest $request)
    {
        $validated = $request->validated();
        $this->friendshipService->unfriend($validated);
        return $this->sendResponse(['message' => __('common.friendship.deleted')]);
    }

    public function getFriendship(Request $request, User $user)
    {
        $friendship = $this->friendshipService->getFriendship(auth()->id(), $user->id);
        if($friendship) return $this->sendResponse(FriendshipResource::make($friendship));
        return $this->sendError(["message" => "Friendship not found"], Response::HTTP_NOT_FOUND);
    }

    public function getFriendRequest(Request $request)
    {
        $friendships = $this->friendshipService->getFriendRequest(auth()->id(), $request->perPage);
        return $this->sendPaginateResponse(FriendshipResource::collection($friendships));
    }

    public function getMutualFriends(Request $request, User $user)
    {
        return $this->sendPaginateResponse(UserResource::collection($this->friendshipService->getMutualFriends(auth()->user(), $user)));
    }

    public function getSuggestionFriends(Request $request)
    {
        return $this->sendResponse(SuggestionFriendResource::collection($this->friendshipService->getSuggestionFriends(auth()->user())));
    }
}
