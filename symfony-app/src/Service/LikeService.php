<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Like;
use App\Entity\Photo;
use App\Entity\User;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;

class LikeService
{
    public function __construct(
        private LikeRepository $likeRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function likePhoto(User $user, Photo $photo): void
    {
        try {
            $like = new Like();
            $like->setUser($user);
            $like->setPhoto($photo);

            $this->entityManager->persist($like);
            
            $photo->setLikeCounter($photo->getLikeCounter() + 1);
            $this->entityManager->persist($photo);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            throw new \Exception('Something went wrong while liking the photo');
        }
    }

    public function unlikePhoto(User $user, Photo $photo): void
    {
        $like = $this->likeRepository->findLike($user, $photo);

        if ($like) {
            $this->entityManager->remove($like);
            
            $photo->setLikeCounter($photo->getLikeCounter() - 1);
            $this->entityManager->persist($photo);
            $this->entityManager->flush();
        }
    }

    public function hasUserLikedPhoto(User $user, Photo $photo): bool
    {
        return $this->likeRepository->findLike($user, $photo) !== null;
    }

    public function toggleLike(User $user, Photo $photo): bool
    {
        if ($this->hasUserLikedPhoto($user, $photo)) {
            $this->unlikePhoto($user, $photo);
            return false;
        }

        $this->likePhoto($user, $photo);
        return true;
    }

    public function getUserLikes(?User $user, array $photos): array
    {
        if (!$user || empty($photos)) {
            return [];
        }

        $userLikes = $this->likeRepository->findUserLikesForPhotos($user, $photos);

        $result = [];

        foreach ($userLikes as ['photoId' => $photoId]) {
            $result[$photoId] = true;
        }

        return $result;
    }
}
