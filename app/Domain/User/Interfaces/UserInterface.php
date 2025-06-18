<?php
namespace Domain\User\Interfaces;

interface UserInterface
{
    public function changeName(string $newName): void;
}