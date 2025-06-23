<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Application\User\SearchUserService;
use Application\User\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private SearchUserService $searchUserService;
    private UserService $userService;

    public function __construct(SearchUserService $searchUserService, UserService $userService)
    {
        $this->searchUserService = $searchUserService;
        $this->userService = $userService;
    }

    public function search(Request $request)
    {
        $query = $request->input('keyword');
        $users = $this->searchUserService->search($query); // trả về array

        if (!empty($users)) {
            return response()->json($users, 200);
        }

        return response()->json(['message' => 'No users found'], 404);
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Lấy user hiện tại

        $data = $request->only(['name', 'email']);
        // Xử lý ảnh nếu có upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');

            // Tạo tên file duy nhất
            $fileName = uniqid() . '.' . $avatar->getClientOriginalExtension();

            // Lưu vào thư mục storage/app/public/
            $path = $avatar->storeAs('', $fileName, 'public');

            // Lưu đường dẫn vào database (chỉ cần phần sau 'public/')
            $data['avatar'] = $fileName;
        }

        $updatedUser = $this->userService->updateProfile($user->id, $data);

        return redirect()->back()->with('success', 'Profile updated successfully.')->with('user', $updatedUser);
    }
}
