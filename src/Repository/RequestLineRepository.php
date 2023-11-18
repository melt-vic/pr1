<?php

namespace App\Repository;

use App\Entity\RequestLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RequestLine>
 *
 * @method RequestLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestLine[]    findAll()
 * @method RequestLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestLine::class);
    }

    //    /**
    //     * @return RequestLine[] Returns an array of RequestLine objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RequestLine
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
