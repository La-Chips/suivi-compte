<?php

namespace App\Controller;

use App\Entity\Ligne;
use App\Entity\Filter;
use App\Entity\Categorie;
use App\Form\CreateFilterType;
use App\Form\CreateCategorieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'settings')]
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

        $categorie = new Categorie();
        $createCategorie = $this->createForm(CreateCategorieType::class, $categorie);
        $createCategorie->handleRequest($request);


        if ($createCategorie->isSubmitted() && $createCategorie->isValid()) {
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('settings');
        }


        $filter = new Filter();
        $createFilter = $this->createForm(CreateFilterType::class, $filter);
        $createFilter->handleRequest($request);

        if ($createFilter->isSubmitted() && $createFilter->isValid()) {
            $em->persist($filter);
            $em->flush();

            return $this->redirectToRoute('settings');
        }

        return $this->render('settings/index.html.twig', [/*  */
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

    #[Route('/sync', name: 'sync')]
    public function sync()
    {
        $em = $this->getDoctrine()->getManager();
        $lignes = $this->getDoctrine()->getRepository(Ligne::class)->findBy(['categorie' => null]);
        $filters = $this->getDoctrine()->getRepository(Filter::class)->findAll();

        $revenu = $this->getDoctrine()->getRepository(Categorie::class)->find(5);
        foreach ($lignes as $ligne) {
            if ($ligne->getMontant() > 0) {
                $ligne->setCategorie($revenu);
                $em->flush();
                continue;
            }
            foreach ($filters as $filter) {
                $libelle = strtolower($ligne->getLibelle());
                $kw = strtolower($filter->getKeyword());
                if (str_contains($libelle, $kw)) {
                    $ligne->setCategorie($filter->getCategorie());
                    $em->flush();
                }
            }
        }


        return $this->redirectToRoute('settings');
    }
}