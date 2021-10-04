<?php

namespace App\Repository;

use App\Entity\Ligne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ligne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ligne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ligne[]    findAll()
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

    public function getMonth($year)
    {
        $qb = $this->createQueryBuilder('line')
            ->select('DISTINCT MONTHNAME(line.date) as month')
            ->where('YEAR(line.date) = :year')
            ->setParameter('year', $year);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getSumCatByMonth($monthname)
    {
        $qb = $this->createQueryBuilder('line')
            ->LeftJoin('line.categorie', 'cat')
            ->select('cat.libelle as libelle , ROUND(sum(line.montant),2) as total')
            ->where('MONTHNAME(line.date) = :monthname')
            ->setParameter('monthname', $monthname)
            ->groupBy('cat');

        $result = $qb->getQuery()->getResult();

        $out = array();

        foreach ($result as $key => $value) {
            $out[$value['libelle']] = $value['total'];
        }

        return $out;
    }

    public function sumByMonthByCat()
    {
        $monthname = $this->getMonth(2021);

        $out = array();

        foreach ($monthname as $key => $value) {
            $value = $value['month'];
            $out[$value] = $this->getSumCatByMonth($value);
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

    public function findByMonth($year, $monthname, $sort, $order)
    {
        $qb = $this->createQueryBuilder('l')
            ->where('MONTHNAME(l.date) = :month and YEAR(l.date) = :year')
            ->setParameters(array('year' => $year, 'month' => $monthname));
        if ($sort == null) {
            $qb->orderBy('l.date', 'DESC');
        } else {
            $sort = 'l.' . $sort;
            $qb->orderBy($sort, $order);
        }

        return $qb->getQuery()->getResult();
    }

    public function sumDu()
    {
        $qb = $this->createQueryBuilder('l')
            ->select('sum(l.montant) as total')
            ->where('l.statut = 1');

        return $qb->getQuery()->getScalarResult();
    }
    public function sumToPay()
    {
        $qb = $this->createQueryBuilder('l')
            ->select('sum(l.montant) as total')
            ->where('l.statut = 2');

        return $qb->getQuery()->getScalarResult();
    }

    public function sum()
    {
        $qb = $this->createQueryBuilder('l')
            ->select('sum(l.montant) as total');

        return $qb->getQuery()->getScalarResult();
    }

    public function sumByMonth($monthname)
    {
        $qb = $this->createQueryBuilder('l')
            ->select('sum(l.montant) as total')
            ->where('MONTHNAME(l.date) = :month')
            ->setParameter('month', $monthname);

        return round($qb->getQuery()->getScalarResult()[0]['total'], 2);
    }
}