<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionService
{
    private SessionInterface $session;

    public function __construct(
        RequestStack $requestStack
    ) {
        $session = $requestStack->getSession();

        if ($session === null) {
            throw new \RuntimeException('Session is not available.');
        }

        $this->session = $session;
    }

    public function getUserId(): ?int
    {
        $userId = $this->session->get('user_id');

        return $userId !== null
            ? (int) $userId
            : null;
    }

    public function getUsername(): ?string
    {
        $username = $this->session->get('username');

        return $username !== null
            ? (string) $username
            : null;
    }

    public function setUser(User $user): void
    {
        $this->session->set('user_id', $user->getId());
        $this->session->set('username', $user->getUsername());
    }

    public function clear(): void
    {
        $this->session->clear();
    }
}