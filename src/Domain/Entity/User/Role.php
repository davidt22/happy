<?php

namespace App\Domain\Entity\User;

enum Role: string
{
    case User = 'ROLE_USER';
    case Manager = 'ROLE_MANAGER';
}