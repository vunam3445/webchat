<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Application\User\SearchUserService;

class UserController extends Controller
{
    private SearchUserService $searchUserService;

    public function __construct(SearchUserService $searchUserService)
    {
        $this->searchUserService = $searchUserService;
    }

    public function search(Request $request)
    {
        $query = $request->input('keyword');
        $users = $this->searchUserService->search($query); // trả về array

        if (!empty($users)) {
            return response()->json($users, 200); // không bọc trong ['user' => ...]
        }

        return response()->json(['message' => 'No users found'], 404);
    }
}
