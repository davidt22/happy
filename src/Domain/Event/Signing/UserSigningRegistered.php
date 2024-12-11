<?php

namespace App\Domain\Event\Signing;

use App\Domain\Event\DomainEvent;

class UserSigningRegistered implements DomainEvent
{
    public int $userId;
    private \DateTimeImmutable $datetime;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->datetime = new \DateTimeImmutable();
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->datetime;
    }
}