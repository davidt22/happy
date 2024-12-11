<?php

namespace App\Domain\ObjectMother;

use App\Domain\Entity\Company\Company;
use App\Domain\Entity\Company\CompanyEndTime;
use App\Domain\Entity\Company\CompanyName;
use App\Domain\Entity\Company\CompanyStartTime;
use App\Domain\Entity\Company\CompanyWorkingDay;
use App\Domain\Entity\Company\WorkingHours;
use Doctrine\Common\Collections\ArrayCollection;

class CompanyObjectMother
{
    public static function aCompany(): Company
    {
        return new Company(
            1,
            new CompanyName('company'),
            new CompanyStartTime('08:00'),
            new CompanyEndTime('20:00'),
            new CompanyWorkingDay([
                'MONDAY',
                'TUESDAY',
                'WEDNESDAY',
                'THURSDAY',
                'FRIDAY',
            ]),
            new WorkingHours(8),
            new ArrayCollection()
        );
    }
}