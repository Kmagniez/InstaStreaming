<?php

namespace App\Repository;

use App\Entity\GenreMovie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GenreMovie|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenreMovie|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenreMovie[]    findAll()
 * @method GenreMovie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreMovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenreMovie::class);
    }

    // /**
    //  * @return GenreMovie[] Returns an array of GenreMovie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GenreMovie
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
