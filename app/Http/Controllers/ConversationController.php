<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Application\Friendship\FriendService;
use Illuminate\Support\Facades\Auth;
use Application\Conversation\ConversationService;
use Application\Conversation\MessageService;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    public function __construct(
        private FriendService $friendService,
        private ConversationService $conversationService,
        private MessageService $messageService
    ) {}
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['msg' => 'Phiên đăng nhập đã hết. Vui lòng đăng nhập lại.']);
        }

        // $friends = $this->friendService->getFriends(Auth::id());
        $friends = $this->conversationService->getConversationsByUserId(Auth::id());
        Log::info('Conversations: ', ['conversations' => $friends]);
        $friendsPendingRequests = $this->friendService->getPendingRequests(Auth::id());
        Log::info('Friends: ', ['friends' => $friends]);
        return view('page.chat', compact('friendsPendingRequests', 'friends')); // Assuming you have a view for listing conversations
    }

    public function getConversation(string $id)
    {
        $friend_id = $id;
        $friends = $this->conversationService->getConversationsByUserId(Auth::id());

        $friendsPendingRequests = $this->friendService->getPendingRequests(Auth::id());
        $conversation = $this->conversationService->create(Auth::id(), $friend_id);
        $messages = $this->messageService->getMessages($conversation->id);

        // $html = view('components.chats.chat-window', compact('conversation', 'messages'))->render();

        // return response()->json(['html' => $html]);
        return view('page.chat', compact('conversation', 'messages', 'friendsPendingRequests', 'friends'));
    }

    public function getGroupConversation(string $id)
    {
        $conversation = $this->conversationService->findConversationById($id);
        if (!$conversation) {
            return redirect()->route('conversations.index')->withErrors(['msg' => 'Cuộc trò chuyện không tồn tại.']);
        }
        $friends = $this->conversationService->getConversationsByUserId(Auth::id());
                Log::info('Conversations: ', ['conversations' => $friends]);

        $friendsPendingRequests = $this->friendService->getPendingRequests(Auth::id());
        $messages = $this->messageService->getMessages($id);

        return view('page.chat', compact('conversation', 'messages','friendsPendingRequests', 'friends'));
    }
    // Tạo nhóm mới
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'user_ids' => 'required|array|min:1',
            // 'user_ids.*' => 'uuid|exists:users,id',
        ]);

        $userId = Auth::id();
        // $userIds = $request->input('user_ids');
        $userIds[] = $userId; // Thêm chính mình vào nhóm

        $conversation = $this->conversationService->createGroup($request->input('name'), array_unique($userIds));

        return redirect()->route('conversations.getGroupConversation', ['id' => $conversation->id]);
    }

    // Thêm thành viên vào nhóm
    public function addMembers(Request $request, string $conversationId)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'uuid|exists:users,id',
        ]);

        $conversation = $this->conversationService->addMembersToGroup($conversationId, $request->input('user_ids'));

        return response()->json(['message' => 'Thêm thành viên thành công', 'conversation' => $conversation]);
    }

    // Xoá thành viên khỏi nhóm
    public function removeMember(string $conversationId, string $userId)
    {
        $conversation = $this->conversationService->removeMemberFromGroup($conversationId, $userId);

        return response()->json(['message' => 'Xoá thành viên thành công', 'conversation' => $conversation]);
    }

    // Xem chi tiết nhóm
    public function showGroup(string $conversationId)
    {
        $conversation = $this->conversationService->findConversationById($conversationId);
        $messages = $this->messageService->getMessages($conversationId);

        return view('page.chat-group', compact('conversation', 'messages'));
    }
}
