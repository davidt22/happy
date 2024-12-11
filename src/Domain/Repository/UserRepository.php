<?php

namespace App\Domain\Repository;

use App\Domain\Entity\User\User;
use App\Domain\Entity\User\UserEmail;

interface UserRepository
{
    public function findByEmail(UserEmail $email): User;
}