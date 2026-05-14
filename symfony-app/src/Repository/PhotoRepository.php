<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\PhotoFilterDto;
use App\Entity\Photo;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private EntityManagerInterface $entityManager
    ) 
    {
        parent::__construct($registry, Photo::class);
    }

    public function findByFilters(PhotoFilterDto $f): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.user', 'u')
            ->addSelect('u')
            ->orderBy('p.id', 'ASC');

        if ($f->location) {
            $queryBuilder->andWhere('LOWER(p.location) LIKE LOWER(:location)')
                ->setParameter('location', "%{$f->location}%");
        }

        if ($f->camera) {
            $queryBuilder->andWhere('LOWER(p.camera) LIKE LOWER(:camera)')
                ->setParameter('camera', "%{$f->camera}%");
        }

        if ($f->description) {
            $queryBuilder->andWhere('LOWER(p.description) LIKE LOWER(:description)')
                ->setParameter('description', "%{$f->description}%");
        }

        if ($f->username) {
            $queryBuilder->andWhere('LOWER(u.username) LIKE LOWER(:username)')
                ->setParameter('username', "%{$f->username}%");
        }

        if ($f->takenFrom) {
            $queryBuilder->andWhere('p.takenAt >= :from')
                ->setParameter('from', $f->takenFrom);
        }

        if ($f->takenTo) {
            $queryBuilder->andWhere('p.takenAt <= :to')
                ->setParameter('to', $f->takenTo);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }


    public function createPhoto(string $imageUrl, User $user, ?string $location = null, ?string $description = null, ?string $camera = null): Photo
    {
        $photo = new Photo();
        $photo->setImageUrl($imageUrl);
        $photo->setUser($user);
        $photo->setLocation($location);
        $photo->setDescription($description);
        $photo->setCamera($camera);

        $this->entityManager->persist($photo);
        $this->entityManager->flush();

        return $photo;
    }
}
