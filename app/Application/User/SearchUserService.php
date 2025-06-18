<?php

namespace Application\User;

use Domain\User\Interfaces\UserRepositoryInterface;
use Domain\User\Entity\User as DomainUser;
class SearchUserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function search(string $query): ?array
    {
        $users = $this->userRepository->search($query); // Trả về mảng DomainUser

        return array_map(function (DomainUser $user) {
            return [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'avatar' => $user->getAvatar(),
            ];
        }, $users);
    }
}
