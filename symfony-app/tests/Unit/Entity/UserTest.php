<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Photo;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIdIsNullByDefault(): void
    {
        $user = new User();

        $this->assertNull(
            $user->getId()
        );
    }

    public function testPhotosCollectionIsInitialized(): void
    {
        $user = new User();

        $this->assertInstanceOf(
            Collection::class,
            $user->getPhotos()
        );

        $this->assertCount(
            0,
            $user->getPhotos()
        );
    }

    public function testSetAndGetUsername(): void
    {
        $user = new User();

        $result = $user->setUsername('john');

        $this->assertSame(
            'john',
            $user->getUsername()
        );

        $this->assertSame(
            $user,
            $result
        );
    }

    public function testSetAndGetEmail(): void
    {
        $user = new User();

        $result = $user->setEmail(
            'john@example.com'
        );

        $this->assertSame(
            'john@example.com',
            $user->getEmail()
        );

        $this->assertSame(
            $user,
            $result
        );
    }

    public function testSetAndGetName(): void
    {
        $user = new User();

        $result = $user->setName('John');

        $this->assertSame(
            'John',
            $user->getName()
        );

        $this->assertSame(
            $user,
            $result
        );
    }

    public function testNameCanBeNull(): void
    {
        $user = new User();

        $user->setName(null);

        $this->assertNull(
            $user->getName()
        );
    }

    public function testSetAndGetLastName(): void
    {
        $user = new User();

        $result = $user->setLastName('Doe');

        $this->assertSame(
            'Doe',
            $user->getLastName()
        );

        $this->assertSame(
            $user,
            $result
        );
    }

    public function testLastNameCanBeNull(): void
    {
        $user = new User();

        $user->setLastName(null);

        $this->assertNull(
            $user->getLastName()
        );
    }

    public function testSetAndGetAge(): void
    {
        $user = new User();

        $result = $user->setAge(30);

        $this->assertSame(
            30,
            $user->getAge()
        );

        $this->assertSame(
            $user,
            $result
        );
    }

    public function testAgeCanBeNull(): void
    {
        $user = new User();

        $user->setAge(null);

        $this->assertNull(
            $user->getAge()
        );
    }

    public function testSetAndGetBio(): void
    {
        $user = new User();

        $result = $user->setBio(
            'Symfony developer'
        );

        $this->assertSame(
            'Symfony developer',
            $user->getBio()
        );

        $this->assertSame(
            $user,
            $result
        );
    }

    public function testBioCanBeNull(): void
    {
        $user = new User();

        $user->setBio(null);

        $this->assertNull(
            $user->getBio()
        );
    }

    public function testSetAndGetPhoenixAppToken(): void
    {
        $user = new User();

        $result = $user->setPhoenixAppToken(
            'secret-token'
        );

        $this->assertSame(
            'secret-token',
            $user->getPhoenixAppToken()
        );

        $this->assertSame(
            $user,
            $result
        );
    }

    public function testPhoenixAppTokenCanBeNull(): void
    {
        $user = new User();

        $user->setPhoenixAppToken(null);

        $this->assertNull(
            $user->getPhoenixAppToken()
        );
    }

    public function testAddPhoto(): void
    {
        $user = new User();

        $photo = $this->createMock(Photo::class);

        $photo
            ->expects($this->once())
            ->method('setUser')
            ->with($user);

        $result = $user->addPhoto($photo);

        $this->assertCount(
            1,
            $user->getPhotos()
        );

        $this->assertTrue(
            $user->getPhotos()->contains($photo)
        );

        $this->assertSame(
            $user,
            $result
        );
    }

    public function testAddPhotoDoesNotDuplicate(): void
    {
        $user = new User();

        $photo = $this->createMock(Photo::class);

        $photo
            ->expects($this->once())
            ->method('setUser');

        $user->addPhoto($photo);
        $user->addPhoto($photo);

        $this->assertCount(
            1,
            $user->getPhotos()
        );
    }

    public function testRemovePhoto(): void
    {
        $user = new User();

        $photo = new Photo();

        $user->addPhoto($photo);

        $this->assertCount(
            1,
            $user->getPhotos()
        );

        $this->assertSame(
            $user,
            $photo->getUser()
        );

        $result = $user->removePhoto($photo);

        $this->assertCount(
            0,
            $user->getPhotos()
        );

        $this->assertNull(
            $photo->getUser()
        );

        $this->assertSame(
            $user,
            $result
        );
    }
}