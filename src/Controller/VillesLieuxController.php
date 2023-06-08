<?php

namespace App\Controller;

use App\Entity\Lieu;
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
        $lieux = ($ville == null) ? [] : $ville->getLieux() ->toArray();

        $test = new Lieu();
        $test -> setNom('Choisissez un lieu');

        array_unshift($lieux, $test);

        return $this -> json($lieux, Response::HTTP_OK, [], ['groups' => 'listeLieux']);
    }
}
