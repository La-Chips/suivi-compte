<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/compagny')]
class CompagnyController extends AbstractController
{
    #[Route('/', name: 'compagny')]
    public function index(): Response
    {
        return $this->render('compagny/index.html.twig', [
            'controller_name' => 'CompagnyController',
        ]);
    }
    #[Route('/devis', name: 'devis')]
    public function devis(): Response
    {
        return $this->render('compagny/index.html.twig', [
            'controller_name' => 'CompagnyController',
        ]);
    }
    #[Route('/facture', name: 'facture')]
    public function facture(): Response
    {
        return $this->render('compagny/index.html.twig', [
            'controller_name' => 'CompagnyController',
        ]);
    }
}