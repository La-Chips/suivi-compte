<?php

namespace App\Controller;

use App\Entity\ExtentionIcon;
use App\Entity\File;
use App\Entity\Folder;
use App\Form\UploadFilesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/folder')]
class FolderController extends AbstractController
{
    #[Route('/{id}', name: 'folder')]
    public function index(Request $request, $id): Response
    {
        $rootFolder = $this->getDoctrine()->getRepository(Folder::class)->find($id);

        $em = $this->getDoctrine()->getManager();

        $uploadFilesForm = $this->createForm(UploadFilesType::class);
        $uploadFilesForm->handleRequest($request);

        if ($uploadFilesForm->isSubmitted() && $uploadFilesForm->isValid()) {
            $rootFolder = $request->request->get('rootFolder');

            $files = $uploadFilesForm->get('files')->getData();
            $dir = $this->getParameter('folder');
            $rootFolder = $this->getDoctrine()->getRepository(Folder::class)->find($rootFolder);


            foreach ($files as $file) {
                $originalFilename = $file->getClientOriginalName();
                $ext = $file->guessExtension();
                $filename = uniqid() . '.' . $ext;
                $size = $file->getSize() / 1024;
                $size = round($size, 2);
                $file->move($dir, $filename);

                $ext = $this->getDoctrine()->getRepository(ExtentionIcon::class)->findOneBy(array('extension' => '.' . $ext));

                $file = new File($originalFilename, $filename, $size);
                $file->setFolder($rootFolder);
                $file->setExtension($ext);

                $em->persist($file);
                $em->flush();
            }
        }

        return $this->render('folder/index.html.twig', [
            'active' => 'folder',
            'rootFolder' => $rootFolder,
            'uploadFilesForm' => $uploadFilesForm->createView(),
        ]);
    }

    #[Route('/new/item', name: 'folder.new')]
    public function new(Request $request)
    {
        $name = $request->request->get('name');
        $root = $request->request->get('root');
        $rootFolder = $this->getDoctrine()->getRepository(Folder::class)->find($root);


        $em = $this->getDoctrine()->getManager();

        $folder = new Folder($name);
        $em->persist($folder);
        $em->flush();

        $rootFolder->addFolder($folder);
        $em->flush();









        return $this->redirectToRoute('folder', array('id' => $rootFolder->getId()));
    }
}