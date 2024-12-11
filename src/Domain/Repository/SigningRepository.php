<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Signing\Signing;
use App\Domain\Entity\User\User;

interface SigningRepository
{
    public function searchByUser(User $user): array;

    public function saveSigning(Signing $signing): void;

    public function findAll(): array;
}