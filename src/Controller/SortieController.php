<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sortie', name: 'create_sortie')]
    public function index(Request $request, EntityManagerInterface $entityManager, EtatRepository $etatRepository): Response
    {
        $participant = $this ->getUser();
        $sortie = new Sortie();
        $formSortie = $this->createForm(SortieFormType::class, $sortie);

        $formSortie->handleRequest($request);
        if ($formSortie->isSubmitted() && $formSortie->isValid()) {
            //On récupère les données du formulaire
            $sortie = $formSortie->getData();
            $sortie -> setCampus($participant -> getCampus());
            $etat = $formSortie->get('enregistrer')->isClicked()
                ? $etatRepository-> findOneBy(['libelle'=>'Créée'])
                :$etatRepository-> findOneBy(['libelle'=>'Ouverte']);;
            $sortie ->setEtat($etat);
            $sortie -> setOrganisateur($participant);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'La sortie à bien été créée');
        }

        return $this->render('sortie/createSortie.html.twig', [
            'formSortie' => $formSortie->createView(),
            'participant' => $participant,
        ]);
    }

    #[Route('/sortie/ConsulterSortie/{id}', name: 'consulter_sortie')]
    public function consulterSortie(int $id, SortieRepository $sortieRepository)
    {
        $sortie = $sortieRepository -> findOneBy(['id' => $id]);
        return $this->render('sortie/consulterSortie.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/sortie/ModifierSortie/{id}', name: 'modif_sortie')]
    public function ModifierSortie(int $id, SortieRepository $sortieRepository, Request $request, EntityManagerInterface $entityManager, EtatRepository $etatRepository)
    {
        $participant = $this ->getUser();
        $sortie = $sortieRepository -> findOneBy(['id' => $id]);
        $formSortie = $this->createForm(SortieFormType::class, $sortie);

        $formSortie->handleRequest($request);
        if ($formSortie->isSubmitted() && $formSortie->isValid()) {
            //On récupère les données du formulaire
            $sortie = $formSortie->getData();

            if ($formSortie->get('enregistrer')->isClicked()) {
                $etat = $etatRepository-> findOneBy(['libelle'=>'Créée']);
            }
            elseif ($formSortie->get('publier')->isClicked()) {
                $etat = $etatRepository-> findOneBy(['libelle'=>'Ouverte']);
            }
            else {
                $etat = $etatRepository-> findOneBy(['libelle'=>'Annulée']);
            }
            $sortie ->setEtat($etat);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'La sortie à bien été modifiée');
        }


        return $this->render('sortie/modifierSortie.html.twig', [
             'formSortie' => $formSortie->createView(),
             'sortie' =>$sortie,
             'participant' => $participant,
        ]);
    }

    #[Route('/sortie/inscription/{id}', name: 'inscription_sortie')]
    public function inscriptionSortie(int $id, SortieRepository $sortieRepository, EntityManagerInterface $entityManager)
    {
        $sortie = $sortieRepository -> findOneBy(['id' => $id]);
        /** @var  Participant $participant */
        $participant = $this ->getUser();
        $sortie->addParticipant($participant);
        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success', "Vous êtes inscrit !");

        return $this->redirectToRoute('consulter_sortie',
            ["id" => $id]);
    }

    #[Route('/sortie/desinscription/{id}', name: 'desinscription_sortie')]
    public function desinscriptionSortie(int $id, SortieRepository $sortieRepository, EntityManagerInterface $entityManager)
    {
        $sortie = $sortieRepository -> findOneBy(['id' => $id]);
        /** @var  Participant $participant */
        $participant = $this ->getUser();
        $sortie->removeParticipant($participant);
        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success', "Vous vous êtes désinscrit !");

        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/sortie/annule/{id}', name: 'annule_sortie')]
    public function annuleSortie(int $id, SortieRepository $sortieRepository, EntityManagerInterface $entityManager)
    {
        $sortie = $sortieRepository -> findOneBy(['id' => $id]);
        /** @var  Participant $participant */
        $participant = $this ->getUser();
        $sortie->removeParticipant($participant);
        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success', "Vous vous êtes désinscrit !");

        return $this->redirectToRoute('app_accueil');
    }

}
