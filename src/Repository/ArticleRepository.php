<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\DocBlock\Description;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


     // pour l'input type search, une requête SQL classique de Symfony ne conviendra pas.
    // la sélection des articles dépendra du mot clé entré dans Search (donc LIKE %motclé%)
    public function findArticleBySearch($search) : array {

        return $this->createQueryBuilder('a')

            ->andWhere('a.description LIKE :search')
            ->setParameter('search', "%" . $search . "%")
            ->getQuery()
            ->getResult()
        ;
    }


    public function findArticlesByFilter($filter): array
    {

        return $this->createQueryBuilder('a')
        ->orderBy("a.date", $filter)
        // ->setParameter('filter', $filter)
        ->getQuery()
        ->getResult()
        ;
    }
}
