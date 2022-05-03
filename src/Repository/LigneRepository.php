<?php

namespace App\Repository;

use App\Entity\Ligne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\Client\Curl\User;

/**
 * @method Ligne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ligne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ligne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ligne::class);
    }

    public function exist(Ligne $ligne)
    {
        $qb = $this->createQueryBuilder('l')
            ->where('DAY(l.date) = DAY(:date)')
            ->andWhere('MONTH(l.date) = MONTH(:date)')
            ->andWhere('YEAR(l.date) = YEAR(:date)')
            ->andWhere('l.type = :type')
            ->andWhere('l.montant = :montant')

            ->setParameters(array(
                'date' => $ligne->getDate(),
                'type' => $ligne->getType(),
                'montant' => $ligne->getMontant(),
            ));

        return count($qb->getQuery()->getResult()) > 0;
    }

    public function findAll()
    {
        $qb = $this->createQueryBuilder('l')
            ->orderBy('l.date', 'DESC');


        return $qb->getQuery()->getResult();
    }

    public function getYears()
    {
        $qb = $this->createQueryBuilder('line')
            ->select('DISTINCT YEAR(line.date) as year');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getMonth($year,$user)
    {
        $qb = $this->createQueryBuilder('line')
            ->select('DISTINCT MONTHNAME(line.date) as month,MONTH(line.date) as monthId')
            ->where('YEAR(line.date) = :year and line.user = :user')
            ->orderby('monthId', 'ASC')
            ->setParameters(array(
                'year' => $year,
                'user' => $user,
            ));

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getSumCatByMonth($monthname, $year,$user)
    {
        $qb = $this->createQueryBuilder('line')
            ->InnerJoin('line.categorie', 'cat')
            ->select('cat.libelle as libelle , ROUND(sum(line.montant),2) as total')
            ->where('MONTHNAME(line.date) = :monthname and YEAR(line.date) = :year and line.user = :user')
            ->setParameters(array(
                'monthname' => $monthname,
                'year' => $year,
                'user' => $user,
            ))
            ->groupBy('cat');
        $result = $qb->getQuery()->getResult();
        $out = array();

        foreach ($result as $key => $value) {
            $out[$value['libelle']] = $value['total'];
        }
        return $out;
    }

    public function sumByMonthByCat($year,$user): array
    {
        $monthname = $this->getMonth($year,$user);
        $out = array();

        foreach ($monthname as $key => $value) {
            $value = $value['month'];
            $out[$value] = $this->getSumCatByMonth($value, $year,$user);
        }


        return $out;
    }

    // /**
    //  * @return Ligne[] Returns an array of Ligne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ligne
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByMonth($year, $monthname, $sort, $order,$user)
    {
        $qb = $this->createQueryBuilder('l')
            ->where('MONTHNAME(l.date) = :month and YEAR(l.date) = :year and l.user = :user')
            ->setParameters(array('year' => $year, 'month' => $monthname, 'user' => $user));
        if ($sort == null) {
            $qb->orderBy('l.date', 'DESC');
        } else {
            $sort = 'l.' . $sort;
            $qb->orderBy($sort, $order);
        }

        return $qb->getQuery()->getResult();
    }

    public function sumDu($user): array|int|string
    {

        $qb = $this->createQueryBuilder('l')
            ->select('sum(l.montant) as total')
            ->where('l.statut = 1 and l.user = :user')
            ->setParameter('user', $user->getId());

        return $qb->getQuery()->getScalarResult();
    }
    public function sumToPay($user)
    {
        $qb = $this->createQueryBuilder('l')
            ->select('sum(l.montant) as total')
            ->where('l.statut = 2 and l.user = :user')
            ->setParameter('user', $user->getId());

        return $qb->getQuery()->getScalarResult();
    }

    public function sum($user)
    {
        $qb = $this->createQueryBuilder('l')
            ->select('sum(l.montant) as total')
            ->where('l.user = :user')
            ->setParameter('user', $user->getId());

        return $qb->getQuery()->getScalarResult();
    }

    public function sumByMonth($monthname,$year,$user)
    {

        $qb = $this->createQueryBuilder('l')
            ->select('sum(l.montant) as total')
            ->where('MONTHNAME(l.date) = :month and YEAR(l.date) = :year and l.user = :user')
            ->setParameters(array('year' => $year, 'month' => $monthname, 'user' => $user));

        return round($qb->getQuery()->getScalarResult()[0]['total'], 2);
    }

    public function delete(?ligne $lastligne)
    {
        $em = $this->getEntityManager();
        $em->remove($lastligne);
        $em->flush();
    }
}