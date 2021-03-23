<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    /**
     * @param  array  $productData
     *
     * @return \App\Entity\Product
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(array $productData): Product
    {
        $product = new Product();
        $product->setName($productData['name']);
        $product->setPrice($productData['price']);
        $product->setRating($productData['rating']);
        $product->setVariations(json_encode($productData['variations'] ?? []));

        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();

        return $product;
    }

    /**
     * @param  \App\Entity\Product  $product
     * @param $productData
     *
     * @return \App\Entity\Product|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Product $product, $productData): Product
    {
        $product->setName($productData['name']);
        $product->setPrice($productData['price']);
        $product->setRating($productData['rating']);
        $product->setVariations(json_encode($productData['variations'] ?? []));

        $this->getEntityManager()->flush();

        return $product;
    }

    /**
     * @param $product
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($product): void
    {
        $this->getEntityManager()->remove($product);
        $this->getEntityManager()->flush();
    }
}
