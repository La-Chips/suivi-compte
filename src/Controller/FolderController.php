<?php

namespace App\Controller;

use App\Entity\Folder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/folder')]
class FolderController extends AbstractController
{
    #[Route('/', name: 'folder')]
    public function index(): Response
    {

        $folders = $this->getDoctrine()->getRepository(Folder::class)->findAll();

        return $this->render('folder/index.html.twig', [
            'active' => 'folder',
            'folders' => $folders,
        ]);
    }

    #[Route('/new', name: 'folder.new')]
    public function new(): Response
    {

        $folders = $this->getDoctrine()->getRepository(Folder::class)->findAll();

        return $this->render('folder/index.html.twig', [
            'active' => 'folder',
            'folders' => $folders,
        ]);
    }
}