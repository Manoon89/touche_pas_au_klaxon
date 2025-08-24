<?php

namespace App\Repository;

use App\Entity\Journey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Journey>
 */
class JourneyRepository extends ServiceEntityRepository
{
    /**
     * Constructeur
     * 
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Journey::class);
    }

    //    /**
    //     * @return Journey[] Returns an array of Journey objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Journey
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findWithAgencies(int $id): ?Journey
    {
        return $this->createQueryBuilder('j')
            ->leftJoin('j.departureAgency', 'da')
            ->addSelect('da')
            ->leftJoin('j.arrivalAgency', 'aa')
            ->addSelect('aa')
            ->where('j.journeyId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUpcomingAvailableJourneys(): array
{
    return $this->createQueryBuilder('j')
        ->leftJoin('j.departureAgency', 'da')->addSelect('da')
        ->leftJoin('j.arrivalAgency', 'aa')->addSelect('aa')
        ->where('j.departureDate >= :now')
        ->andWhere('j.availableSeats > 0')
        ->setParameter('now', new \DateTime())
        ->orderBy('j.departureDate', 'ASC')
        ->getQuery()
        ->getResult();
}

}