<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PhotoFilterDto
{
    #[Assert\Length(max: 255)]
    public ?string $location = null;

    #[Assert\Length(max: 255)]
    public ?string $camera = null;

    #[Assert\Length(max: 255)]
    public ?string $description = null;

    #[Assert\Length(max: 255)]
    public ?string $username = null;

    public ?\DateTimeImmutable $takenFrom = null;
    public ?\DateTimeImmutable $takenTo = null;

    #[Assert\Callback]
    public function validateDateRange(ExecutionContextInterface $context): void
    {
        if ($this->takenFrom && $this->takenTo && $this->takenFrom > $this->takenTo) {
            $context->buildViolation('Date "from" cannot be greater than "to".')
                ->atPath('takenFrom')
                ->addViolation();
        }
    }
}