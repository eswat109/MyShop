<?php

namespace App\Repository;

use App\Entity\Whishlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Whishlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Whishlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Whishlist[]    findAll()
 * @method Whishlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WhishlistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Whishlist::class);
    }

    // /**
    //  * @return Whishlist[] Returns an array of Whishlist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Whishlist
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
