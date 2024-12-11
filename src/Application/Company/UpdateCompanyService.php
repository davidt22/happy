<?php

namespace App\Application\Company;

use App\Domain\Entity\Company\Company;
use App\Domain\Entity\Company\CompanyEndTime;
use App\Domain\Entity\Company\CompanyStartTime;
use App\Domain\Entity\Company\CompanyWorkingDay;
use App\Domain\Repository\CompanyRepository;

class UpdateCompanyService
{
    private CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function execute(Company $company, string $start, string $end, array $workDays): void
    {
        $company->setStartTime(new CompanyStartTime($start));
        $company->setEndTime(new CompanyEndTime($end));
        $company->setAvailableWorkingDays(new CompanyWorkingDay($workDays));

        $this->companyRepository->saveSchedule($company);
    }

}