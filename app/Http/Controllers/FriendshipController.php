<?php

namespace App\Http\Controllers;

use Application\Friendship\FriendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class FriendshipController extends Controller
{
    private FriendService $friendService;

    public function __construct(FriendService $friendService)
    {
        $this->friendService = $friendService;
    }

    public function sendRequest(Request $request)
    {
        $friendId = $request->input('friend_id');
        if (!$friendId) {
            return response()->json(['error' => 'Friend ID is required.'], 400);
        }
  
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
        $this->friendService->sendFriendRequest(Auth::id(),$friendId);
        return response()->json(['message' => 'Request sent.']);
    }

    public function acceptRequest(Request $request)
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'User ID is required.'], 400);
        }
  
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
        $this->friendService->acceptRequest($userId, Auth::id());
        return response()->json(['message' => 'Request accepted.']);
    }

    public function rejectRequest(Request $request)
    {
        $friendId = $request->input('friend_id');
        if (!$friendId) {
            return response()->json(['error' => 'Friend ID is required.'], 400);
        }
  
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
        $this->friendService->rejectRequest(Auth::id(),$friendId);
        return response()->json(['message' => 'Request rejected.']);
    }

    public function blockUser(Request $request)
    {
        $friendId = $request->input('friend_id');
        if (!$friendId) {
            return response()->json(['error' => 'Friend ID is required.'], 400);
        }
  
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
        $this->friendService->blockUser(Auth::id(),$friendId);
        return response()->json(['message' => 'User blocked.']);
    }
    public function getFriends()
    {
        return response()->json($this->friendService->getFriends(Auth::id()));
    }

    public function getPendingRequests()
    {
        return response()->json($this->friendService->getPendingRequests(Auth::id()));
    }
}
