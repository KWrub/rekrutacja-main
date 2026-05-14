<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Like;
use App\Entity\Photo;
use App\Entity\User;
use App\Repository\LikeRepository;

class LikeService
{
    public function __construct(
        private LikeRepository $likeRepository
    ) {}

    public function likePhoto(User $user, Photo $photo): void
    {
        try {
            $this->likeRepository->likePhoto($user, $photo);
        } catch (\Throwable $e) {
            throw new \Exception('Something went wrong while liking the photo');
        }
    }

    public function unlikePhoto(User $user, Photo $photo): void
    {
        $this->likeRepository->unlikePhoto($user, $photo);
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
