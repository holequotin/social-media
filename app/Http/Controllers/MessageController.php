<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessageController extends BaseApiController
{
    public function __construct(protected MessageService $messageService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, User $user)
    {
        $messages = $this->messageService->getMessageWithUser($user, $request->perPage);
        return $this->sendPaginateResponse(MessageResource::collection($messages));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $validated = $request->validated();
        $message = $this->messageService->storeMessage($validated);
        return $this->sendResponse(MessageResource::make($message), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }

    public function getLastMessages(Request $request)
    {
        $messages = $this->messageService->getLastMessages(auth()->user());
        return $this->sendPaginateResponse(MessageResource::collection($messages));
    }
}
