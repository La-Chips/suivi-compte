<?php

namespace App\Controller\CRUD;

use App\Entity\Statut;
use App\Form\StatutType;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/status'),IsGranted('ROLE_ADMIN')]
class StatusController extends AbstractController
{
    #[Route('/', name: 'app_status_index', methods: ['GET'])]
    public function index(StatutRepository $statutRepository): Response
    {
        return $this->render('crud/status/index.html.twig', [
            'statuts' => $statutRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_status_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $statut = new Statut();
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($statut);
            $entityManager->flush();

            return $this->redirectToRoute('app_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/status/new.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_status_show', methods: ['GET'])]
    public function show(Statut $statut): Response
    {
        return $this->render('crud/status/show.html.twig', [
            'statut' => $statut,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_status_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Statut $statut, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/status/edit.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_status_delete', methods: ['POST'])]
    public function delete(Request $request, Statut $statut, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statut->getId(), $request->request->get('_token'))) {
            $entityManager->remove($statut);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_status_index', [], Response::HTTP_SEE_OTHER);
    }
}
