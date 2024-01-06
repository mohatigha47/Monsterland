<?php

namespace App\Repository;

use App\Entity\Monstre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Monstre>
 *
 * @method Monstre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Monstre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Monstre[]    findAll()
 * @method Monstre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonstreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Monstre::class);
    }

    public function getMonstresEventuellementRoyaume()
    {
        return $this->createQueryBuilder("m")
            ->select("m.nom", "r.id")
            ->leftJoin("m.royaume", "r")
            ->getQuery()
            ->getResult();
    }

    public function action16($nom)
    {
        return $this->createQueryBuilder("m")
            ->select("m.nom", "r.id")
            ->leftJoin("m.royaume", "r")
            ->where("m.nom LIKE :nom")
            ->setParameter('nom', $nom )
            ->getQuery()
            ->getResult();
    }

    public function action17()
    {
        return $this->createQueryBuilder("m")
            ->select("m.nom", "r.id")
            ->getQuery()
            ->getResult();
    }

    //    /**
//     * @return Monstre[] Returns an array of Monstre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Monstre
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}