<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Like;
use App\Entity\Photo;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function findLike(User $user, Photo $photo): ?Like
    {
        return $this->createQueryBuilder('l')
            ->where('l.user = :user')
            ->andWhere('l.photo = :photo')
            ->setParameter('user', $user)
            ->setParameter('photo', $photo)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUserLikesForPhotos(User $user, array $photos): array
    {
        if (empty($photos)) {
            return [];
        }

        return $this->createQueryBuilder('l')
            ->select('IDENTITY(l.photo) as photoId')
            ->where('l.user = :user')
            ->andWhere('l.photo IN (:photos)')
            ->setParameter('user', $user)
            ->setParameter('photos', $photos)
            ->getQuery()
            ->getArrayResult();
    }

    public function likePhoto(User $user, Photo $photo): void
    {
        $like = new Like();
        $like->setUser($user);
        $like->setPhoto($photo);

        $this->getEntityManager()->persist($like);
        
        $photo->setLikeCounter($photo->getLikeCounter() + 1);
        $this->getEntityManager()->persist($photo);
        $this->getEntityManager()->flush();
    }

    public function unlikePhoto(User $user, Photo $photo): void
    {
        $like = $this->findLike($user, $photo);

        if ($like) {
            $this->getEntityManager()->remove($like);
            
            $photo->setLikeCounter($photo->getLikeCounter() - 1);
            $this->getEntityManager()->persist($photo);
            $this->getEntityManager()->flush();
        }
    }
}
