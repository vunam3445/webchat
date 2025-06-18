<?php

namespace Application\Auth;

use Domain\User\Interfaces\UserRepositoryInterface;
use Domain\User\Entity\User;
use App\Models\User as UserModel; // Giả sử bạn có một model User
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $userData): void
    {
        $user = new User(
            id: Str::uuid()->toString(),                        // UUID mới
            name: $userData['name'],
            email: $userData['email'],
            phone: $userData['phone'],
            passwordHash: Hash::make($userData['password']),    // Mã hoá mật khẩu
            avatar: $userData['avatar'] ?? null
        );

        $this->userRepository->register($user); // Truyền entity User vào
    }

    public function login(string $phone, string $password): ?User
    {
        // Gọi repository để kiểm tra thông tin đăng nhập
        $user = $this->userRepository->login($phone, $password);
        return $user; // Trả về DomainUser hoặc null
    }
    public function logout(): void
    {
        $user = Auth::user();
        if ($user) {
            $this->userRepository->logout($user->id);
            Auth::logout();
        }
    }
}
