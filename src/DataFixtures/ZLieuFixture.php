<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ZLieuFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Récupérer tous les campus existants
        $villesRepository = $manager->getRepository(Ville::class);
        $villes = $villesRepository->findAll();

        for ($i = 0; $i < 10; $i++) {
            $lieu = new Lieu();
            $lieu -> setRue($faker->address());
            $lieu->setNom($faker->name());
            // Assigner un campus aléatoire au participant
            $randomVille = $faker->randomElement($villes);
            $lieu->setVille($randomVille);
            $manager->persist($lieu);
        }

        $manager->flush();
    }
}
