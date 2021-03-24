<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Rating;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function checkRatingExists(User $user, Product $product): ?Rating
    {
        return $this->findBy([ 'user' => $user, 'product' => $product ])[0] ?? null;
    }

    public function save(User $user, Product $product, int $value): Rating
    {
        $rating = new Rating();
        $rating->setRating($value);
        $rating->setUser($user);
        $rating->setProduct($product);
        $product->vote($value);

        $this->getEntityManager()->persist($rating);
        $this->getEntityManager()->flush();

        return $rating;
    }
}
