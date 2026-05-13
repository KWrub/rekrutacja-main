<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\AuthTokenRepository;

class AuthService
{
    public function __construct(
        private AuthTokenRepository $authTokenRepository
    ) {}

    public function authenticate(string $username, string $token): ?User
    {
        $authToken = $this->authTokenRepository->findByToken($token);

        if (!$authToken) {
            return null;
        }

        $user = $authToken->getUser();

        if (!$user || $user->getUsername() !== $username) {
            return null;
        }

        return $user;
    }
}
