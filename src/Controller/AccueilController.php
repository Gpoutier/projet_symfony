<?php

namespace App\Controller;

use App\Form\FiltresFormType;
use App\Modele\FiltreSortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(SortieRepository $sortieRepository, Request $request): Response
    {
        $participant = $this ->getUser();
        if (!$participant) {
            throw $this -> createNotFoundException("Vous devez être connecté");
        }

        $sorties = $sortieRepository -> findAll();
        $columnsName = $this -> columnsName();

        $filtre = new FiltreSortie();
        $filtre ->setCampus($participant->getCampus());
        $filtre ->setIduser($participant->getId());
        $filtresFormType = $this->createForm(FiltresFormType::class, $filtre);
        $filtresFormType->handleRequest($request);
        $sorties = $sortieRepository->sortieFiltre($filtre);

        return $this->render('accueil/index.html.twig', [
            'FiltresFormType' => $filtresFormType->createView(),
            'sorties' => $sorties,
            'columnsName' => $columnsName,
        ]);
    }

    public function columnsName() {
        return $columnsName = ['Nom de la sortie', 'Date de la sortie','Clôture', 'Inscrits/places', 'Etat', 'Inscrit', 'Organisateur', 'Actions'];
    }
}
