<?php

namespace App\Controller;

use App\Entity\Profesionnel;
use App\Form\ProfesionnelType;
use App\Repository\ProfesionnelRepository;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function new(Request $request, SluggerInterface $slugger, ): Response
    {
        $profesionnel = new Profesionnel();
        $form = $this->createForm(ProfesionnelType::class, $profesionnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile == null) {
                $this->addFlash('error', 'Aucun image sélectionnée');
                return $this->redirectToRoute('profesionnel_new');
            }
            $path = $this->pathImage($imageFile, $slugger);
            $profesionnel->setImage($path);
            $entityManager = $this->getDoctrine()->getManager();
            try {
                $entityManager->persist($profesionnel);
                $entityManager->flush();
            } catch (ORMException $e) {
                $this->addFlash('error', 'Erreur lors de l\'enregistrement');
                return $this->redirectToRoute('profesionnel_new');
            }
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
    public function edit(Request $request, Profesionnel $profesionnel,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProfesionnelType::class, $profesionnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $path = $this->pathImage($imageFile, $slugger);
                $profesionnel->setImage($path);
            }
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
    private function pathImage($imageFile,SluggerInterface $slugger)
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
        try {
            $imageFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        return "/contact/logo/".$newFilename;
    }
}
