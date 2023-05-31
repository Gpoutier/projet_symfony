<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        $user = $this -> getUser();
        $form = $this->createForm(ProfilFormType::class,  $user);

        return $this->render('profil/index.html.twig', [
            'formProfil' => $form->createView(),
        ]);
    }
}
