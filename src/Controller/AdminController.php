<?php

namespace App\Controller;

use App\Entity\Statut;
use App\Form\AddStatutType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(Request $request): Response
    {
        $statuts = $this->getDoctrine()->getRepository(Statut::class)->findAll();
        $em = $this->getDoctrine()->getManager();


        $statut = new Statut();
        $form = $this->createForm(AddStatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($statut);
            $em->flush();
        }

        return $this->render('admin/index.html.twig', [
            'statuts' => $statuts,
            'add_statut' => $form->createView(),
        ]);
    }
}