<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $villes = [
            ['codePostal' => '75001', 'nom' => 'Paris'],
            ['codePostal' => '69001', 'nom' => 'Lyon'],
            ['codePostal' => '31000', 'nom' => 'Toulouse'],
            ['codePostal' => '49000', 'nom' => 'Angers'],
            ['codePostal' => '35000', 'nom' => 'Rennes'],
            // Ajoutez d'autres villes ici
        ];

        foreach ($villes as $villeData) {
            $ville = new Ville();
            $ville->setCodePostal($villeData['codePostal']);
            $ville->setNom($villeData['nom']);
            $manager->persist($ville);

            // Ajoutez une référence à chaque entité Ville
            $this->addReference('ville_' . $villeData['codePostal'], $ville);
        }

        $manager->flush();
    }
}
