<?php

namespace App\Domain\Entity\Company;

use App\Domain\Exception\Signing\WorkingDayException;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Embeddable()
 */
final class CompanyWorkingDay
{
    private const array AVAILABLE_WORKING_DAYS = [
        WorkingDay::MONDAY->value,
        WorkingDay::TUESDAY->value,
        WorkingDay::WEDNESDAY->value,
        WorkingDay::THURSDAY->value,
        WorkingDay::FRIDAY->value
    ];

    /**
     * @ORM\Column(name="available_working_days", type="simple_array")
     */
    private array $value;

    public function __construct(array $value)
    {
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    public function __toString(): string
    {
        return implode('-', $this->value);
    }

    public function value(): array
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * @throws WorkingDayException
     */
    private function ensureIsValid(array $value): void
    {
        if (!empty(array_diff($value, self::AVAILABLE_WORKING_DAYS))) {
            throw new WorkingDayException('Dias de trabajo no validos');
        }
    }
}