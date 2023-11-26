<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ManagerRegistry $mr,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function insertUser(User $user, bool $isAnonymous = true): void
    {
        if ($isAnonymous) {
            $user->setType($this->mr->getRepository(UserType::class)->find(1));
        } else {
            $user->setType($this->mr->getRepository(UserType::class)->find(2));
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
        }
        if (!$this->userExists($user->getEmail())) {
            $em = $this->mr->getManager();
            $em->persist($user);
            $em->flush();
        }
    }

    private function userExists(string $email): bool
    {
        return null !== $this->userRepository->findOneBy(['email' => $email]);
    }
}
