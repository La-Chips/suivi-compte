<?php

namespace App\Controller;

use App\Entity\Profesionnel;
use App\Form\ProfesionnelType;
use App\Repository\ProfesionnelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profesionnel')]
class ProfesionnelController extends AbstractController
{
    #[Route('/', name: 'profesionnel_index', methods: ['GET'])]
    public function index(ProfesionnelRepository $profesionnelRepository): Response
    {
        return $this->render('profesionnel/index.html.twig', [
            'profesionnels' => $profesionnelRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'profesionnel_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $profesionnel = new Profesionnel();
        $form = $this->createForm(ProfesionnelType::class, $profesionnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profesionnel);
            $entityManager->flush();

            return $this->redirectToRoute('profesionnel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profesionnel/new.html.twig', [
            'profesionnel' => $profesionnel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'profesionnel_show', methods: ['GET'])]
    public function show(Profesionnel $profesionnel): Response
    {
        return $this->render('profesionnel/show.html.twig', [
            'profesionnel' => $profesionnel,
        ]);
    }

    #[Route('/{id}/edit', name: 'profesionnel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Profesionnel $profesionnel): Response
    {
        $form = $this->createForm(ProfesionnelType::class, $profesionnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('compagny', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profesionnel/edit.html.twig', [
            'profesionnel' => $profesionnel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'profesionnel_delete', methods: ['POST'])]
    public function delete(Request $request, Profesionnel $profesionnel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$profesionnel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($profesionnel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('compagny', [], Response::HTTP_SEE_OTHER);
    }
}
