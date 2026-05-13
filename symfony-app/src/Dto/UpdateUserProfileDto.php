<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserProfileDto
{
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Phoenix app token must be at least 1 character long',
        maxMessage: 'Phoenix app token cannot be longer than 255 characters'
    )]
    #[Assert\NotBlank(message: 'Phoenix app token is required')]
    public ?string $phoenixAppToken = null;
}
