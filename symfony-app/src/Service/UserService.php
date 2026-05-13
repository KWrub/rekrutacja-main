<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\SessionService;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }

    public function getUserByUsername(string $username): ?User
    {
        return $this->userRepository->findByUsername($username);
    }

    public function getCurrentUser(SessionService $sessionService): ?User
    {
        $userId = $sessionService->getUserId();

        if ($userId === null) {
            return null;
        }

        return $this->getUserById($userId);
    }

    public function updateUserProfile(User $user, string $phoenixAppToken): User
    {
        $user->setPhoenixAppToken($phoenixAppToken);
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
