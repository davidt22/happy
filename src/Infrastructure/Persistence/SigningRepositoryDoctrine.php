<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\Signing\Signing;
use App\Domain\Entity\User\User;
use App\Domain\Repository\SigningRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SigningRepositoryDoctrine extends ServiceEntityRepository implements SigningRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Signing::class);
    }

    public function searchByUser(User $user): array
    {
//        $queryBuilder = $this->getEntityManager()->createQueryBuilder('s');
//
//        return $queryBuilder
//            ->select('signing')
//            ->from(Signing::class, 'signing')
//            ->where('signing.user = :user')
//            ->setParameter('user', $user)
//            ->getQuery()
//            ->getResult();

        return $this->findBy(['user' => $user]);
    }

    public function saveSigning(Signing $signing): void
    {
        $this->getEntityManager()->persist($signing);
        $this->getEntityManager()->flush();
    }

    public function findAll(): array
    {
        return $this->findBy([], ['user' => 'ASC', 'start.value' => 'DESC']);
    }
}
