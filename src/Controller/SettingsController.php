<?php

namespace App\Controller;

use App\Entity\Ligne;
use App\Entity\Filter;
use App\Entity\Categorie;
use App\Form\CreateFilterType;
use App\Form\CreateCategorieType;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'settings')]
    public function index(Request $request,CategorieRepository $categorieRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $categorieRepository->findBy(['User' => $this->getUser()],['libelle' => 'ASC']);

        $categorie = new Categorie();
        $createCategorie = $this->createForm(CreateCategorieType::class, $categorie);
        $createCategorie->handleRequest($request);


        if ($createCategorie->isSubmitted() && $createCategorie->isValid()) {
            $categorie->setUser($this->getUser());
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('settings');
        }


        $filter = new Filter();
        $createFilter = $this->createForm(CreateFilterType::class, $filter, ['categories' => $categories]);
        $createFilter->handleRequest($request);

        if ($createFilter->isSubmitted() && $createFilter->isValid()) {
            $filter->setUser($this->getUser());
            $em->persist($filter);
            $em->flush();

            return $this->redirectToRoute('settings');
        }
        return $this->render('settings/index.html.twig', [/*  */
            'active' => 'settings',

            'categories' => $categories,
            'createCategorie' => $createCategorie->createView(),
            'createFilter' => $createFilter->createView(),

        ]);
    }

    #[Route('/delete/{option}/{id}', name: 'delete')]
    public function delete($option, $id)
    {
        $em = $this->getDoctrine()->getManager();
        switch ($option) {
            case 'categorie':
                $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
                $em->remove($categorie);
                $em->flush();
                break;
            case 'filter':
                $filter = $this->getDoctrine()->getRepository(Filter::class)->find($id);
                $em->remove($filter);
                $em->flush();
                break;


            default:
                # code...
                break;
        }
        return $this->redirectToRoute('settings');
    }
}