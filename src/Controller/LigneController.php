<?php

namespace App\Controller;

use App\Entity\Ligne;
use App\Entity\Statut;
use App\Form\LigneType;
use App\Repository\LigneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ligne')]
class LigneController extends AbstractController
{
    #[Route('/', name: 'ligne_index', methods: ['GET'])]
    public function index(LigneRepository $ligneRepository): Response
    {
        return $this->render('ligne/index.html.twig', [
            'lignes' => $ligneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'ligne_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {


        $ligne = new Ligne();

        if ($request->query->get('option') != null) {
            $option = $request->query->get('option');
            switch ($option) {
                case 1:
                    $statut = $this->getDoctrine()->getRepository(Statut::class)->find(1);
                    break;
                case 2:
                    $statut = $this->getDoctrine()->getRepository(Statut::class)->find(2);

                    break;

                default:
                    # code...
                    break;
            }
        }
        $form = $this->createForm(LigneType::class, $ligne, array('statut' => $statut));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ligne);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ligne/new.html.twig', [
            'ligne' => $ligne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ligne_show', methods: ['GET'])]
    public function show(Ligne $ligne): Response
    {
        return $this->render('ligne/show.html.twig', [
            'ligne' => $ligne,
        ]);
    }

    #[Route('/{id}/edit', name: 'ligne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ligne $ligne): Response
    {
        $form = $this->createForm(LigneType::class, $ligne,array('date' => $ligne->getDate()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $referer = $request->headers->get('referer');

return $this->redirect($referer);

        }

        return $this->renderForm('ligne/edit.html.twig', [
            'ligne' => $ligne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ligne_delete', methods: ['POST'])]
    public function delete(Request $request, Ligne $ligne): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ligne->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ligne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}