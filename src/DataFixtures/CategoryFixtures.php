<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Créez des catégories
        $category1 = new Category();
        $category1->setName('Manteaux');
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('Bonnets');
        $manager->persist($category2);

        $category1 = new Category();
        $category1->setName('T-shirts');
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('Echarpes');
        $manager->persist($category2);

        // D'autres catégories si nécessaire

        $manager->flush();

    }
}
