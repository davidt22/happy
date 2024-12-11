<?php

namespace App\Domain\Entity;

use App\Domain\Event\DomainEvent;

abstract class AggregateRoot
{
    private array $domainEvents = [];

    protected function recordEvent(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function pullEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}