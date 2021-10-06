<?php

namespace App\Controller\CRUD;

use App\Entity\ExtentionIcon;
use App\Entity\File;
use App\Form\ExtentionIconType;
use App\Repository\ExtentionIconRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/extention/icon')]
class ExtentionIconController extends AbstractController
{
    #[Route('/', name: 'extention_icon_index', methods: ['GET'])]
    public function index(ExtentionIconRepository $extentionIconRepository): Response
    {
        return $this->render('extention_icon/index.html.twig', [
            'extention_icons' => $extentionIconRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'extention_icon_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $extentionIcon = new ExtentionIcon();
        $form = $this->createForm(ExtentionIconType::class, $extentionIcon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $this->getDoctrine()->getRepository(File::class)->findBy(array('extention' => null));
            $entityManager = $this->getDoctrine()->getManager();


            $entityManager->persist($extentionIcon);
            $entityManager->flush();

            foreach ($files as $file) {
                $filename = $file->getOriginalName();
                $filename = explode('.', $filename);
                $ext = end($filename);

                if ($ext == $extentionIcon->getExtension()) {
                    $file->setExtension($extentionIcon);
                    $entityManager->flush();
                }
            }

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('extention_icon/new.html.twig', [
            'extention_icon' => $extentionIcon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'extention_icon_show', methods: ['GET'])]
    public function show(ExtentionIcon $extentionIcon): Response
    {
        return $this->render('extention_icon/show.html.twig', [
            'extention_icon' => $extentionIcon,
        ]);
    }

    #[Route('/{id}/edit', name: 'extention_icon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ExtentionIcon $extentionIcon): Response
    {
        $form = $this->createForm(ExtentionIconType::class, $extentionIcon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('extention_icon/edit.html.twig', [
            'extention_icon' => $extentionIcon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'extention_icon_delete', methods: ['POST'])]
    public function delete(Request $request, ExtentionIcon $extentionIcon): Response
    {
        if ($this->isCsrfTokenValid('delete' . $extentionIcon->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($extentionIcon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('extention_icon_index', [], Response::HTTP_SEE_OTHER);
    }
}