<?php

namespace App\Repository;

use App\Entity\ScheduleExpense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScheduleExpense>
 *
 * @method ScheduleExpense|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScheduleExpense|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScheduleExpense[]    findAll()
 * @method ScheduleExpense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScheduleExpense::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ScheduleExpense $entity, bool $flush = true): void
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
    public function remove(ScheduleExpense $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // findByUser
    public function findByUser(int $user_id): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.bankAccount', 'b')
            ->join('b.user', 'u')
            ->andWhere('u.id = :user')
            ->setParameter('user', $user_id)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return ScheduleExpense[] Returns an array of ScheduleExpense objects
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
    public function findOneBySomeField($value): ?ScheduleExpense
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
