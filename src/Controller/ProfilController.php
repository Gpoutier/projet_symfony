<?php

namespace App\Controller;

use App\Form\ProfilFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class ProfilController extends AbstractController
{
    #[Route('/profil/modifierProfil', name: 'modif_profil')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participant = $this -> getUser();
        if (!$participant) {
            throw $this -> createNotFoundException("Vous devez être connecté");
        }
        $form = $this->createForm(ProfilFormType::class,  $participant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //On récupère les données du formulaire
            $participant = $form->getData();
            //On hache le mdp
            $hashedPassword = $this->passwordHasher->hashPassword($participant, $participant -> getPassword());
            $participant->setPassword($hashedPassword);
            //On sauvegarde en bdd
            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Informations mises à jour');
        }

        return $this->render('profil/modifierProfil.html.twig', [
            'formProfil' => $form->createView(),
        ]);
    }

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/profil/ConsulterProfil/{pseudo}', name: 'consulter_profil')]
    public function consulterProfil(String $pseudo, ParticipantRepository $participantRepository)
    {
        $participant = $participantRepository->findOneBy(['pseudo' => $pseudo]);

        return $this->render('profil/consulterProfil.html.twig', [
            'participant' => $participant,
        ]);
    }
}
