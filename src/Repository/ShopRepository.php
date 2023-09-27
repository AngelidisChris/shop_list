<?php

namespace App\Repository;

use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shop>
 *
 * @method Shop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shop[]    findAll()
 * @method Shop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shop::class);
    }

    /**
     * @return Query Returns an array of Shop objects
     */
    public function findByFilters(?array $shopOwnerIds, ?array $shopCategoryIds, ?string $city): Query
    {
        $qb = $this->createQueryBuilder('s');
        if ($city){
            $qb->andWhere('s.city like :city')
                ->setParameter('city', "%".$city."%");
        }
        if($shopOwnerIds){
            $qb->andWhere('s.shopOwner in (:shopOwnerIds)')
                ->setParameter('shopOwnerIds', $shopOwnerIds);
        }
        if($shopCategoryIds){
            $qb->andWhere('s.shopCategory in (:shopCategoryIds)')
                ->setParameter('shopCategoryIds', $shopCategoryIds);
        }
        return $qb->orderBy('s.id', 'ASC')
            ->getQuery();
    }

//    public function findOneBySomeField($value): ?Shop
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
