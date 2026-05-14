<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\AuthToken;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class AuthTokenTest extends TestCase
{
    public function testConstructorSetsCreatedAt(): void
    {
        $authToken = new AuthToken();

        $this->assertInstanceOf(
            \DateTimeInterface::class,
            $authToken->getCreatedAt()
        );
    }

    public function testSetAndGetToken(): void
    {
        $authToken = new AuthToken();

        $result = $authToken->setToken('my-secret-token');

        $this->assertSame(
            'my-secret-token',
            $authToken->getToken()
        );

        $this->assertSame(
            $authToken,
            $result
        );
    }

    public function testSetAndGetUser(): void
    {
        $authToken = new AuthToken();

        $user = $this->createMock(User::class);

        $result = $authToken->setUser($user);

        $this->assertSame(
            $user,
            $authToken->getUser()
        );

        $this->assertSame(
            $authToken,
            $result
        );
    }

    public function testSetAndGetCreatedAt(): void
    {
        $authToken = new AuthToken();

        $date = new \DateTimeImmutable('2025-01-01 12:00:00');

        $result = $authToken->setCreatedAt($date);

        $this->assertSame(
            $date,
            $authToken->getCreatedAt()
        );

        $this->assertSame(
            $authToken,
            $result
        );
    }

    public function testIdIsNullByDefault(): void
    {
        $authToken = new AuthToken();

        $this->assertNull(
            $authToken->getId()
        );
    }
}