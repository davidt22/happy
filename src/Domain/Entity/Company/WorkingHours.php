<?php

namespace App\Domain\Entity\Company;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Embeddable()
 */
final class WorkingHours
{
    /**
     * @ORM\Column(name="number_hours_work_day", type="float")
     */
    private float $value;

    public function __construct(float $value)
    {
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function ensureIsValid(float $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException(sprintf('El valor "%s" no es válido.', $value));
        }
    }
}