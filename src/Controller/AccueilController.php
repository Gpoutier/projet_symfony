<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(SortieRepository $sortieRepository): Response
    {
        $sorties = $sortieRepository -> findAll();
        $columnsName = $this -> columnsName();

        return $this->render('accueil/index.html.twig', [
            'sorties' => $sorties,
            'columnsName' => $columnsName,
        ]);
    }

    public function columnsName() {
        return $columnsName = ['Nom de la sortie', 'Date de la sortie','Clôture', 'Inscrits/places', 'Etat', 'Inscrit', 'Organisateur', 'Actions'];
    }
}
