<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\PhotoFilterDto;
use App\Entity\Photo;
use App\Repository\PhotoRepository;

class PhotoService
{
    public function __construct(
        private PhotoRepository $photoRepository
    ) {}

    public function getPhotos(PhotoFilterDto $dto): array
    {
        return $this->photoRepository->findByFilters($dto);
    }

    public function getPhotoById(int $photoId): ?Photo
    {
        return $this->photoRepository->find($photoId);
    }
}