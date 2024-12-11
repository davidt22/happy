<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\User\User;
use App\Domain\Entity\User\UserEmail;
use App\Domain\Exception\User\UserNotFoundException;
use App\Domain\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepositoryDoctrine extends ServiceEntityRepository implements UserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws UserNotFoundException
     */
    public function findByEmail(UserEmail $email): User
    {
        $user = $this->findOneBy(['email.value' => $email]);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}