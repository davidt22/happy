<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Company\Company;

interface CompanyRepository
{
    public function saveSchedule(Company $company): void;
}