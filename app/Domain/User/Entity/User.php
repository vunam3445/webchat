<?php

namespace Domain\User\Entity;

use Domain\User\Interfaces\UserInterface;
use Domain\User\Interfaces\UserPasswordInterface;

class User implements UserInterface, UserPasswordInterface
{
    private string $id;
    private string $name;
    private ?string $email;
    private string $phone;
    private ?string $avatar;
    private string $passwordHash;

    public function __construct(string $id, string $name, ?string $email, string $phone, string $passwordHash, ?string $avatar = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->passwordHash = $passwordHash;
        $this->avatar = $avatar;
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
    // seter
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }


    public function changeName(string $newName): void
    {
        $newName = trim($newName);

        if (empty($newName)) {
            throw new \InvalidArgumentException("Tên không được để trống.");
        }

        $this->name = $newName;
    }


    // Implement UserPasswordInterface
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }
    public function changePassword(string $newPassword): void
    {
        $this->passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    }
}
