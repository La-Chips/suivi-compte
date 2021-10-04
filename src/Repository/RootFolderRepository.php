<?php

namespace App\Repository;

use App\Entity\RootFolder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RootFolder|null find($id, $lockMode = null, $lockVersion = null)
 * @method RootFolder|null findOneBy(array $criteria, array $orderBy = null)
 * @method RootFolder[]    findAll()
 * @method RootFolder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RootFolderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RootFolder::class);
    }

    // /**
    //  * @return RootFolder[] Returns an array of RootFolder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RootFolder
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
