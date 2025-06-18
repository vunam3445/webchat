<?php
namespace Domain\User\Interfaces;
use Domain\User\Entity\User;

interface UserRepositoryInterface
{
    public function findById(string $id): ?User;
    public function findByEmail(string $email): ?User;
    public function findByPhone(string $phone): ?User;
    public function save(User $user): void;
    public function register(User $user): void;
    public function login(string $phone, string $password): ?User;
    public function logout(string $userId): void;
    public function updateProfile(User $user): void;
    public function search(string $query): array; 
    public function getFriends(string $userId): array; // Lấy danh sách bạn bè của người dùng
}