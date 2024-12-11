<?php

namespace App\Domain\Entity\Signing;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
final class SigningStart
{
    /**
     * @ORM\Column(name="start", type="datetime_immutable")
     */
    private \DateTimeImmutable $value;

    public function __construct(\DateTimeImmutable $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d H-i-s');
    }

    public function value(): \DateTimeImmutable
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}