<?php

namespace App\DataFixtures;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductDataLoader extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName($faker->randomElement(['Chair', 'Table', 'Bed', 'Ball']));
            $product->setPrice($faker->randomFloat(2, 0, 100));
            $product->setRating($faker->randomFloat(1, 0, 5));
            $manager->persist($product);
        }

        $manager->flush();
    }
}