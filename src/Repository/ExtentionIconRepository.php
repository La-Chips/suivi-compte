<?php

namespace App\Repository;

use App\Entity\ExtentionIcon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExtentionIcon|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtentionIcon|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtentionIcon[]    findAll()
 * @method ExtentionIcon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtentionIconRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtentionIcon::class);
    }

    // /**
    //  * @return ExtentionIcon[] Returns an array of ExtentionIcon objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExtentionIcon
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
