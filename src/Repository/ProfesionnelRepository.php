<?php

namespace App\Repository;

use App\Entity\Profesionnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Profesionnel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profesionnel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profesionnel[]    findAll()
 * @method Profesionnel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfesionnelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profesionnel::class);
    }

    // /**
    //  * @return Profesionnel[] Returns an array of Profesionnel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Profesionnel
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
