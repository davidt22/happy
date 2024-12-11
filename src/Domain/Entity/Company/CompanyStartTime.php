<?php

namespace App\Domain\Entity\Company;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Embeddable()
 */
final class CompanyStartTime
{
    /**
     * @ORM\Column(name="start_time", type="string")
     */
    private string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function value(): string
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
    private function ensureIsValid(string $value): void
    {
        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $value)) {
            throw new InvalidArgumentException(sprintf('El valor "%s" no es válido.', $value));
        }
    }
}