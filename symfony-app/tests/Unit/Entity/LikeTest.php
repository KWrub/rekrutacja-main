<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Like;
use App\Entity\Photo;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class LikeTest extends TestCase
{
    public function testConstructorSetsCreatedAt(): void
    {
        $like = new Like();

        $this->assertInstanceOf(
            \DateTimeInterface::class,
            $like->getCreatedAt()
        );
    }

    public function testIdIsNullByDefault(): void
    {
        $like = new Like();

        $this->assertNull(
            $like->getId()
        );
    }

    public function testSetAndGetUser(): void
    {
        $like = new Like();

        $user = $this->createMock(User::class);

        $result = $like->setUser($user);

        $this->assertSame(
            $user,
            $like->getUser()
        );

        $this->assertSame(
            $like,
            $result
        );
    }

    public function testSetAndGetPhoto(): void
    {
        $like = new Like();

        $photo = $this->createMock(Photo::class);

        $result = $like->setPhoto($photo);

        $this->assertSame(
            $photo,
            $like->getPhoto()
        );

        $this->assertSame(
            $like,
            $result
        );
    }

    public function testSetAndGetCreatedAt(): void
    {
        $like = new Like();

        $date = new \DateTimeImmutable(
            '2025-01-01 12:00:00'
        );

        $result = $like->setCreatedAt($date);

        $this->assertSame(
            $date,
            $like->getCreatedAt()
        );

        $this->assertSame(
            $like,
            $result
        );
    }
}