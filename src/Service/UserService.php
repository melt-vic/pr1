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
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly CartService $cartService
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
        $userInDB = $this->userRepository->findOneBy(['email' => $user->getEmail()]);
        if (!$userInDB) {
            $em = $this->mr->getManager();
            $em->persist($user);
            $em->flush();
            $this->cartService->addUserId($user);
        } else {
            $this->cartService->addUserId($userInDB);
        }
    }
}
