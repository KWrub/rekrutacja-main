<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\SessionService;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
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
}
