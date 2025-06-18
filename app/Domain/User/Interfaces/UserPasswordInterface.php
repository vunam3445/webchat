<?php
namespace Domain\User\Interfaces;

interface UserPasswordInterface
{
    public function verifyPassword(string $password): bool;
    public function changePassword(string $newPassword): void;
}