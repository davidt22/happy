<?php

namespace App\Application\Signing;

use App\Domain\Entity\User\User;
use App\Domain\Repository\SigningRepository;

class FindUserSigningsService
{
    private SigningRepository $signingRepository;

    public function __construct(SigningRepository $signingRepository)
    {
        $this->signingRepository = $signingRepository;
    }

    public function execute(User $user): array
    {
        return $this->signingRepository->searchByUser($user);
    }
}