<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Repository\CategoryRepository;

class ProductFixtures extends Fixture
{
    private Generator $faker;
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->faker = Factory::create('fr_FR');
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager)
    {
        // Récupérer les catégories existantes depuis la base de données
        $categories = $this->categoryRepository->findAll();

        // Vous pouvez maintenant utiliser le tableau $categories pour associer les produits aux catégories existantes.

        // Exemple de boucle pour créer des produits associés à des catégories existantes
        foreach ($categories as $category) {
            for ($i = 0; $i < 50; $i++) {
                $product = new Product();
                $product->setName($this->faker->sentence(3));
                $product->setSlug($this->faker->slug);
                $product->setIllustration("liendelillustration+$i");
                $product->setSubtitle($this->faker->sentence(1));
                $product->setDescription($this->faker->text(100));
                $product->setPrice($this->faker->randomFloat(2, 0));
                $product->setCategory($category); // Associez le produit à la catégorie
                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}