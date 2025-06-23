<?php

namespace Application\User;

use Domain\User\Interfaces\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateProfile(string $userId, array $data): bool
    {
        // Tìm người dùng theo ID
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            return false; // Không tìm thấy
        }

        // Gán dữ liệu từ mảng $data
        if (isset($data['name'])) {
            $user->setName($data['name']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['avatar'])) {
            $user->setAvatar($data['avatar']);
        }

        // Gọi repository để cập nhật
        return (bool) $this->userRepository->updateProfile($user);
    }

    // Các method khác như login, đổi tên, ...
}
