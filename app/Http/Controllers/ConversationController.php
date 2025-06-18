<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Application\Friendship\FriendService;
use Illuminate\Support\Facades\Auth;
use Application\Conversation\ConversationService;
use Application\Conversation\MessageService;

class ConversationController extends Controller
{
    public function __construct(
        private FriendService $friendService,
        private ConversationService $conversationService,
        private MessageService $messageService
    ) {}
    public function index()
    {
        $friends = $this->friendService->getFriends(Auth::id());
        $friendsPendingRequests = $this->friendService->getPendingRequests(Auth::id());

        return view('page.chat', compact('friendsPendingRequests', 'friends')); // Assuming you have a view for listing conversations
    }

    public function getConversation(string $id)
    {
        $friend_id = $id; 
        $friends = $this->friendService->getFriends(Auth::id());
        $friendsPendingRequests = $this->friendService->getPendingRequests(Auth::id());
        $conversation = $this->conversationService->create(Auth::id(), $friend_id);
        $messages = $this->messageService->getMessages($conversation->id);

        // $html = view('components.chats.chat-window', compact('conversation', 'messages'))->render();

        // return response()->json(['html' => $html]);
        return view('page.chat',compact('conversation', 'messages', 'friendsPendingRequests', 'friends'));
    }
}
