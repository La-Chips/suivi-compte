<?php

namespace App\Repository;

use App\Entity\ScheduleRepeat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScheduleRepeat>
 *
 * @method ScheduleRepeat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScheduleRepeat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScheduleRepeat[]    findAll()
 * @method ScheduleRepeat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleRepeatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScheduleRepeat::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ScheduleRepeat $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ScheduleRepeat $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ScheduleRepeat[] Returns an array of ScheduleRepeat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ScheduleRepeat
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
