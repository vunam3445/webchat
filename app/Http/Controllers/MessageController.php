<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Application\Conversation\MessageService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MessageRequest;
use Illuminate\Support\Facades\Log;
class MessageController extends Controller
{
    private MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Gửi tin nhắn trong 1 cuộc trò chuyện.
     */
    public function send(MessageRequest $request, string $conversationId)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $name = Auth::user()->name ?? 'Unknown';
        $avatar = Auth::user()->avatar ?? 'default-avatar.png'; // Giả sử có trường avatar trong User model

        $message = $this->messageService->sendMessage(
            $conversationId,
            $userId,
            $request->input('content'),
            $name,
            $avatar,
            $request->input('type', 'text'),
            $request->input('metadata', [])
        );
        event(new \App\Events\MessageSent($message, $conversationId));


        return response()->json($message);
    }


    /**
     * Lấy các tin nhắn của một cuộc trò chuyện (load more).
     * - Nếu không có `before` thì lấy 20 tin mới nhất.
     * - Nếu có `before` (ISO 8601 timestamp), thì lấy 20 tin trước đó.
     */
    public function getMessages(Request $request, string $conversationId)
    {
        $before = $request->query('before'); // timestamp string hoặc null
        $limit = $request->query('limit', 20);

        $messages = $this->messageService->getMessages($conversationId, $before, $limit);

        return response()->json($messages);
    }
}
