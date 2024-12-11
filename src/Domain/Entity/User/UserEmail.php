<?php

namespace App\Domain\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Embeddable()
 */
final class UserEmail
{
    /**
     * @ORM\Column(name="email", type="string")
     */
    private string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValidEmail($value);
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function email(): string
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
    private function ensureIsValidEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(sprintf('El email "%s" no es válido.', $email));
        }
    }
}