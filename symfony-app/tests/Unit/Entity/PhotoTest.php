<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Photo;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PhotoTest extends TestCase
{
    public function testIdIsNullByDefault(): void
    {
        $photo = new Photo();

        $this->assertNull(
            $photo->getId()
        );
    }

    public function testLikeCounterIsZeroByDefault(): void
    {
        $photo = new Photo();

        $this->assertSame(
            0,
            $photo->getLikeCounter()
        );
    }

    public function testSetAndGetImageUrl(): void
    {
        $photo = new Photo();

        $result = $photo->setImageUrl(
            'https://example.com/photo.jpg'
        );

        $this->assertSame(
            'https://example.com/photo.jpg',
            $photo->getImageUrl()
        );

        $this->assertSame(
            $photo,
            $result
        );
    }

    public function testSetAndGetLocation(): void
    {
        $photo = new Photo();

        $result = $photo->setLocation('Warsaw');

        $this->assertSame(
            'Warsaw',
            $photo->getLocation()
        );

        $this->assertSame(
            $photo,
            $result
        );
    }

    public function testLocationCanBeNull(): void
    {
        $photo = new Photo();

        $photo->setLocation(null);

        $this->assertNull(
            $photo->getLocation()
        );
    }

    public function testSetAndGetDescription(): void
    {
        $photo = new Photo();

        $result = $photo->setDescription(
            'Beautiful sunset'
        );

        $this->assertSame(
            'Beautiful sunset',
            $photo->getDescription()
        );

        $this->assertSame(
            $photo,
            $result
        );
    }

    public function testDescriptionCanBeNull(): void
    {
        $photo = new Photo();

        $photo->setDescription(null);

        $this->assertNull(
            $photo->getDescription()
        );
    }

    public function testSetAndGetCamera(): void
    {
        $photo = new Photo();

        $result = $photo->setCamera(
            'Canon EOS R6'
        );

        $this->assertSame(
            'Canon EOS R6',
            $photo->getCamera()
        );

        $this->assertSame(
            $photo,
            $result
        );
    }

    public function testCameraCanBeNull(): void
    {
        $photo = new Photo();

        $photo->setCamera(null);

        $this->assertNull(
            $photo->getCamera()
        );
    }

    public function testSetAndGetTakenAt(): void
    {
        $photo = new Photo();

        $date = new \DateTimeImmutable(
            '2025-01-01 12:00:00'
        );

        $result = $photo->setTakenAt($date);

        $this->assertSame(
            $date,
            $photo->getTakenAt()
        );

        $this->assertSame(
            $photo,
            $result
        );
    }

    public function testTakenAtCanBeNull(): void
    {
        $photo = new Photo();

        $photo->setTakenAt(null);

        $this->assertNull(
            $photo->getTakenAt()
        );
    }

    public function testSetAndGetUser(): void
    {
        $photo = new Photo();

        $user = $this->createMock(User::class);

        $result = $photo->setUser($user);

        $this->assertSame(
            $user,
            $photo->getUser()
        );

        $this->assertSame(
            $photo,
            $result
        );
    }

    public function testUserCanBeNull(): void
    {
        $photo = new Photo();

        $photo->setUser(null);

        $this->assertNull(
            $photo->getUser()
        );
    }

    public function testSetAndGetLikeCounter(): void
    {
        $photo = new Photo();

        $result = $photo->setLikeCounter(15);

        $this->assertSame(
            15,
            $photo->getLikeCounter()
        );

        $this->assertSame(
            $photo,
            $result
        );
    }
}