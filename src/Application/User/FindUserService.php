<?php

namespace App\Application\User;

use App\Domain\Entity\User\User;
use App\Domain\Entity\User\UserEmail;
use App\Domain\Repository\UserRepository;

class FindUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $email): ?User
    {
        $email = new UserEmail($email);

        return $this->userRepository->findByEmail($email);
    }
}