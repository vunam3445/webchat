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

    

    // Các method khác như login, đổi tên, ...
}