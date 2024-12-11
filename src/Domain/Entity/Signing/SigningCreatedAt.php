<?php

namespace App\Domain\Entity\Signing;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
final class SigningCreatedAt
{
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private \DateTime $value;

    public function __construct(\DateTime $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d H-i-s');
    }

    public function value(): \DateTime
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}