<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\Company\Company;
use App\Domain\Repository\CompanyRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CompanyRepositoryDoctrine extends ServiceEntityRepository implements CompanyRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function saveSchedule(Company $company): void
    {
        $this->getEntityManager()->persist($company);
        $this->getEntityManager()->flush();
    }
}
