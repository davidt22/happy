<?php

namespace App\Domain\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Manager extends User
{
    protected array $roles = [Role::Manager];
}