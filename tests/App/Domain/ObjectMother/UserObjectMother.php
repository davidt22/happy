<?php

namespace App\Domain\ObjectMother;

use App\Domain\Entity\Company\Company;
use App\Domain\Entity\User\Role;
use App\Domain\Entity\User\User;
use App\Domain\Entity\User\UserActive;
use App\Domain\Entity\User\UserEmail;
use App\Domain\Entity\User\UserFirstName;
use App\Domain\Entity\User\UserLastName;
use App\Domain\Entity\User\UserPassword;

class UserObjectMother
{
    public static function aUser(Company $company): User
    {
        return new User(
            1,
            new UserFirstName('name'),
            new UserLastName('lastname'),
            new UserEmail('email@email.com'),
            Role::User->value,
            new UserActive(false),
            new UserPassword('pass'),
            $company
        );
    }
}