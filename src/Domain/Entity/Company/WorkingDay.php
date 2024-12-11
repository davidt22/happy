<?php

namespace App\Domain\Entity\Company;

enum WorkingDay
{
    case MONDAY;
    case TUESDAY;
    case WEDNESDAY;
    case THURSDAY;
    case FRIDAY;
    case SATURDAY;
    case SUNDAY;

    const array AVAILABLE_WORKING_DAYS = [
        self::MONDAY,
        self::TUESDAY,
        self::WEDNESDAY,
        self::THURSDAY,
        self::FRIDAY
    ];
}