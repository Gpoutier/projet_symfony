<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VillesLieuxController extends AbstractController
{
    #[Route('/lieux/{idVille}', name: 'lieux_par_ville', methods: "GET")]
    public function traitementLieux(int $idVille, VilleRepository $villeRepository): Response
    {
        $ville =  $villeRepository -> findOneBy(['id' => $idVille]);
        $lieux = $ville->getLieux();
        $lieuxTableau  = [];
        foreach ($lieux as $lieu) {
            $lieuxTableau[] = [
                'id' => $lieu->getId(),
                'nom' => $lieu->getNom(),
                'rue' => $lieu->getRue(),
                'latitude' => $lieu->getLatitude(),
                'longitude' => $lieu->getLongitude(),
                'codePostal' => $lieu ->getVille() ->getCodePostal(),
            ];
        }

        return new JsonResponse($lieuxTableau);
    }
}
