<?php

namespace App\Application\Signing;

use App\Domain\Repository\SigningRepository;

class FindSigningsService
{
    private SigningRepository $signingRepository;

    public function __construct(SigningRepository $signingRepository)
    {
        $this->signingRepository = $signingRepository;
    }

    public function execute(): array
    {
        return $this->signingRepository->findAll();
    }
}