<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //Requete avec le queryBuilder
    public function findAllGreaterThanPrice($price): array
    {
        $qb = $this->createQueryBuilder('p'); //SELECT * FROM product p
        $qb->andWhere('p.price > :price'); //WHERE p.price > :price
        $qb->setParameter('price', $price);

        $qb->orderBy('p.price', 'ASC'); // ORDER BY price ASC

        return $qb->getQuery()->getResult();//Execute la requete  
    }

    public function findOneGreaterThanPrice($price): Product
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.price > :price') //WHERE p.price > :price
            ->setParameter('price', $price)
            ->orderBy('p.price', 'DESC')
            ->setMaxResults(1)//LImite 1
            ->getQuery();

        return $qb->getOneOrNullResult();
    }

    /**
     * Cette methode doit retourner les 4produits les plus chers de la BDD, appelée sur notre page d'accueil afin d'afficher les 4 produits.
     */
    public function findMoreExpensive(int $number = 4)
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.price', 'DESC')
            ->setMaxResults($number)// 4
            ->getQuery();
        return $qb->getResult();
    }
    
}
