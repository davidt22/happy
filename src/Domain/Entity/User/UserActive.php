<?php

namespace App\Domain\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
final class UserActive
{
    /**
     * @ORM\Column(name="active", type="boolean")
     */
    private string $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value ? '1' : '0';
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}