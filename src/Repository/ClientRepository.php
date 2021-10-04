<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Particulier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findByFilter($q)
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('App\Entity\Particulier', 'p', Join::WITH, 'c.id = p.id')
            ->leftJoin('App\Entity\Profesionnel', 'pro', Join::WITH, 'c.id = pro.id')

            ->andWhere('p.nom LIKE :q or p.prenom LIKE :q or pro.raison_social like :q')
            ->orWhere('p.id LIKE  :q OR pro.id LIKE :q')
            ->orderBy('c.id', 'DESC')
            ->setParameter('q', '%' . $q . '%');

        return $qb->getQuery()->getResult();
    }

    public function findOneByConcat($value): ?Client
    {
        $value = explode('-', $value)[0];
        $value = trim($value);

        return $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTopTenZones()
    {
        $qb = $this->createQueryBuilder('cli');
        $qb = $qb
            ->select($qb->expr()->substring('cli.cp', 1, 2) . ' as dep ,count(cli.id) as nb')
            ->innerJoin('cli.commandes', 'cmd')
            ->andWhere('cmd.status != 4')
            ->groupBy('dep')
            ->orderBy('nb', 'DESC');

        $q = $qb->getQuery()->setMaxResults(10);

        return $q->getResult();
    }
    public function findNbPcbyClient()
    {
        $qb = $this->createQueryBuilder('cli');
        $qb = $qb
            ->select('count(cmd),count(cli)')
            ->innerJoin('cli.commandes', 'cmd')
            ->innerJoin('cmd.contenu', 'art')
            ->innerJoin('art.type', 'cat')
            ->where('cat.id = 16')
            ->andWhere('cmd.status != 4');





        $q = $qb->getQuery();

        return $q->getResult();
    }

    public function getNbClientByType()
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('App\Entity\Particulier', 'p', Join::WITH, 'c.id = p.id')
            ->leftJoin('App\Entity\Profesionnel', 'pro', Join::WITH, 'c.id = pro.id')
            ->select('count(p.id) as Particulier , count(pro.id) as Professionnel');

        $q = $qb->getQuery();

        return $q->getResult()[0];
    }

    public function export()
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('App\Entity\Particulier', 'p', Join::WITH, 'c.id = p.id');

        $array = $qb->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        if (count($array) > 0) {
            array_unshift($array, array_keys($array[0]));
        }

        $qb = $this->createQueryBuilder('c')

            ->innerJoin('App\Entity\Profesionnel', 'pro', Join::WITH, 'c.id = pro.id');

        $array2 = $qb->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        if (count($array2) > 0) {
            array_unshift($array2, array_keys($array2[1]));
            array_unshift($array2, ['']);
        }
        foreach ($array2 as $pro) {
            array_push($array, $pro);
        }

        return $array;
    }
}
