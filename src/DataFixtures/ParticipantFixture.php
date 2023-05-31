<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ParticipantFixture extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        // Récupérer tous les campus existants
        $campusRepository = $manager->getRepository(Campus::class);
        $campus = $campusRepository->findAll();

        for ($i = 0; $i < 10; $i++) {
            $participant = new Participant();
            $participant->setNom($faker->lastName);
            $participant->setPrenom($faker->firstName);
            $participant->setPseudo($faker->userName);
            $participant->setTelephone($faker->phoneNumber);
            $participant->setMail($faker->email);
            $hashedPassword = $this->passwordHasher->hashPassword($participant, '1234');
            $participant->setPassword($hashedPassword);
            $participant->setAdministrateur($faker->boolean);
            $participant->setActif($faker->boolean);

            // Assigner un campus aléatoire au participant
            $randomCampus = $faker->randomElement($campus);
            $participant->setCampus($randomCampus);

            $manager->persist($participant);
        }

        $manager->flush();
    }
}
