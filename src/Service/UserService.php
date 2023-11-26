<?php

namespace App\Service;

use App\Repository\UserRepository;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function userExists(string $email): bool
    {
        return null !== $this->userRepository->findOneBy(['email' => $email]);
    }
}
