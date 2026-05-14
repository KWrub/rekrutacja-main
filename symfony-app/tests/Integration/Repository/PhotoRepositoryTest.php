<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Dto\PhotoFilterDto;
use App\Entity\Photo;
use App\Entity\User;
use App\Repository\PhotoRepository;
use App\Tests\Support\IntegrationTestCase;

class PhotoRepositoryTest extends IntegrationTestCase
{
    private PhotoRepository $photoRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->photoRepository = self::getContainer()
            ->get(PhotoRepository::class);
    }

    private function createUser(string $username): User
    {
        $em = self::getContainer()->get('doctrine')->getManager();

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($username . '@test.com');

        $em->persist($user);
        $em->flush();

        return $user;
    }

    private function createPhoto(
        User $user,
        string $location = 'Warsaw',
        string $camera = 'Canon',
        string $description = 'test photo',
        \DateTimeImmutable $takenAt = null
    ): Photo {
        $em = self::getContainer()->get('doctrine')->getManager();

        $photo = new Photo();
        $photo->setImageUrl('http://test.com/img.jpg');
        $photo->setUser($user);
        $photo->setLocation($location);
        $photo->setCamera($camera);
        $photo->setDescription($description);
        $photo->setTakenAt($takenAt ?? new \DateTimeImmutable('2025-01-01'));

        $em->persist($photo);
        $em->flush();

        return $photo;
    }

    public function testCreatePhoto(): void
    {
        $em = self::getContainer()->get('doctrine')->getManager();

        $user = $this->createUser('john');

        $photo = $this->photoRepository->createPhoto(
            'http://image.com/1.jpg',
            $user,
            'Warsaw',
            'Nice photo',
            'Canon'
        );

        $this->assertInstanceOf(Photo::class, $photo);
        $this->assertSame('Warsaw', $photo->getLocation());

        $this->assertNotNull($photo->getId());
    }

    public function testFindByFiltersByLocation(): void
    {
        $user = $this->createUser('john');

        $this->createPhoto($user, 'Warsaw');
        $this->createPhoto($user, 'Berlin');

        $dto = new PhotoFilterDto();
        $dto->location = 'Wars';

        $result = $this->photoRepository->findByFilters($dto);

        $this->assertCount(1, $result);
        $this->assertSame('Warsaw', $result[0]->getLocation());
    }

    public function testFindByFiltersByCamera(): void
    {
        $user = $this->createUser('john');

        $this->createPhoto($user, 'Warsaw', 'Canon');
        $this->createPhoto($user, 'Warsaw', 'Nikon');

        $dto = new PhotoFilterDto();
        $dto->camera = 'Canon';

        $result = $this->photoRepository->findByFilters($dto);

        $this->assertCount(1, $result);
        $this->assertSame('Canon', $result[0]->getCamera());
    }

    public function testFindByFiltersByUsername(): void
    {
        $user1 = $this->createUser('john');
        $user2 = $this->createUser('mike');

        $this->createPhoto($user1, 'Warsaw');
        $this->createPhoto($user2, 'Warsaw');

        $dto = new PhotoFilterDto();
        $dto->username = 'john';

        $result = $this->photoRepository->findByFilters($dto);

        $this->assertCount(1, $result);
        $this->assertSame('john', $result[0]->getUser()->getUsername());
    }

    public function testFindByFiltersByDateRange(): void
    {
        $user = $this->createUser('john');

        $this->createPhoto(
            $user,
            'Warsaw',
            'Canon',
            'old',
            new \DateTimeImmutable('2024-01-01')
        );

        $this->createPhoto(
            $user,
            'Warsaw',
            'Canon',
            'new',
            new \DateTimeImmutable('2025-01-01')
        );

        $dto = new PhotoFilterDto();
        $dto->takenFrom = new \DateTimeImmutable('2024-06-01');

        $result = $this->photoRepository->findByFilters($dto);

        $this->assertCount(1, $result);
        $this->assertSame('new', $result[0]->getDescription());
    }

    public function testFindByFiltersEmptyDtoReturnsAll(): void
    {
        $user = $this->createUser('john');

        $this->createPhoto($user);
        $this->createPhoto($user);

        $dto = new PhotoFilterDto();

        $result = $this->photoRepository->findByFilters($dto);

        $this->assertGreaterThanOrEqual(2, count($result));
    }
}