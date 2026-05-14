<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;

class PhotoImportService
{
    public function __construct(
        private PhoenixApiService $phoenixApiService,
        private PhotoService $photoService
    ) {}

    /**
     * Import photos from Phoenix API for a given user
     *
     * @param User $user The user to import photos for
     * @return int Number of imported photos
     * @throws \Exception If import fails
     */
    public function importPhotosFromPhoenix(User $user): int
    {
        $phoenixAppToken = $user->getPhoenixAppToken();

        if (!$phoenixAppToken) {
            throw new \InvalidArgumentException('Phoenix app token is not configured for this user.');
        }

        $photosData = $this->phoenixApiService->getPhotos($phoenixAppToken)['photos'] ?? [];

        $importedCount = 0;

        if (is_array($photosData)) {
            foreach ($photosData as $photoData) {
                if (isset($photoData['photo_url'])) {
                    $this->photoService->createPhoto(
                        $photoData['photo_url'],
                        $user,
                        $photoData['location'] ?? null,
                        $photoData['description'] ?? null,
                        $photoData['camera'] ?? null
                    );
                    $importedCount++;
                }
            }
        }

        return $importedCount;
    }
}