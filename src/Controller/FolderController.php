<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Folder;
use App\Entity\RootFolder;
use App\Form\UploadFilesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/folder')]
class FolderController extends AbstractController
{
    #[Route('/{id}', name: 'folder')]
    public function index(Request $request,$id): Response
    {
        $rootFolder = $this->getDoctrine()->getRepository(Folder::class)->find($id);
        $folders = $this->getDoctrine()->getRepository(RootFolder::class)->findBy(['rootFolder' => $rootFolder]);

        $em = $this->getDoctrine()->getManager();

        $uploadFilesForm = $this->createForm(UploadFilesType::class);
        $uploadFilesForm->handleRequest($request);

        if ($uploadFilesForm->isSubmitted() && $uploadFilesForm->isValid()){
            $rootFolder = $request->request->get('rootFolder');

            $files = $uploadFilesForm->get('files')->getData();
            $dir = $this->getParameter('folder');
            $rootFolder = $this->getDoctrine()->getRepository(Folder::class)->find($rootFolder);


            foreach ($files as $file) {
                $originalFilename = $file->getClientOriginalName();
                $filename = uniqid().'.'.$file->guessExtension();
                $size = $file->getSize() / 1024;
                $size = round($size,2);
                $file->move($dir, $filename);

                $file = new File($originalFilename,$filename,$size);
                $file->setFolder($rootFolder);
                
                $em->persist($file);
                $em->flush();

            }
        }

        return $this->render('folder/index.html.twig', [
            'active' => 'folder',
            'folders' => $folders,
            'rootFolder' => $rootFolder,
            'uploadFilesForm' => $uploadFilesForm->createView(),
        ]);
    }

    #[Route('/new', name: 'folder.new')]
    public function new(Request $request)
    {
        $name = $request->request->get('name');
        $root = $request->request->get('root');
        $rootFolder = $this->getDoctrine()->getRepository(Folder::class)->find($root);


        $em = $this->getDoctrine()->getManager();

        $folder = new Folder($name);
        $link = new RootFolder($rootFolder,$folder);
        $folder->setRootFolder($link);


        $em->persist($folder);
        $em->flush();


        $em->persist($link);
        $em->flush();

        die();
    }
}