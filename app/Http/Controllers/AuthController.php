<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Application\Auth\AuthService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    //
    public function __construct(protected AuthService $authService)
    {
        // Constructor logic if needed
    }
    public function index()
    {
        return view('auth.login_register');
    }
    public function register(UserRequest $request)
    {
        $data = $request->validated();
        $this->authService->registerUser($data);
        return redirect('/auth')->with('success', 'Đăng ký thành công!');
    }
    public function login(Request $request)
    {
        $data = $request->only('identifier', 'password');
        $user = $this->authService->login($data['identifier'], $data['password']);

        if ($user) {
            $request->session()->regenerate(); // Tái tạo session cho bảo mật
            return redirect()->intended('/conversations')->with('message', 'Login successful');
        }


        return back()->withErrors(['identifier' => 'Số điện thoại hoặc mật khẩu không đúng.'])->withInput();
    }

    

  
    public function logout()
    {
        try {
            Auth::logout();
            $this->authService->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/auth')->with('message', 'Logout successful');
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return back()->withErrors(['message' => 'Logout failed']);
        }
    }
}
