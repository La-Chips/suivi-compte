<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Ligne;
use App\Entity\Statut;
use App\Form\CreateCategorieType;
use App\Form\LigneType;
use App\Repository\CategorieRepository;
use App\Repository\LigneRepository;
use App\Repository\StatutRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function new(Request $request, LigneRepository $ligneRepository, StatutRepository $statutRepository, UserRepository $userRepository, CategorieRepository $categorieRepository, SessionInterface $session): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categories = $categorieRepository->findBy(['User' => $this->getUser()], ['libelle' => 'ASC']);
        $ligne = new Ligne();
        $statut = null;
        if ($request->query->get('option') != null) {
            $option = $request->query->get('option');

            $statut = match ($option) {
                "1" => $statutRepository->findOneBy(['id' => 1]),
                "2" => $statutRepository->findOneBy(['id' => 2]),
                default => null,
            };
        }

        $categorie = new Categorie();
        $createCategorie = $this->createForm(CreateCategorieType::class, $categorie);
        $createCategorie->handleRequest($request);


        if ($createCategorie->isSubmitted() && $createCategorie->isValid()) {
            $categorie->setUser($this->getUser());
            $entityManager->persist($categorie);
            $entityManager->flush();
            return $this->redirectToRoute('ligne_new');
        }

        $form = $this->createForm(LigneType::class, $ligne, ['statut' => $statut, 'categories' => $categories, 'user_id' => $this->getUser()->getId()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $shared_people = $request->request->get('ligne')['owner'] ?? [];

            if($ligne->getOwner()->count() == 0){
                foreach ($shared_people as $user_id) {
                    $user = $userRepository->findOneBy(['id' => $user_id]);
                    $ligne->addOwner($user);
                }
            }

            $ligne->setUser($this->getUser());
            $ligne->setOrigine(0); //zero means it's a ligne add manually by the user
            $entityManager->persist($ligne);
            $entityManager->flush();

            return $this->redirectToRoute('home_redirect', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ligne/new.html.twig', [
            'ligne' => $ligne,
            'form' => $form,
            'createCategorie' => $createCategorie,
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
    public function edit(Request $request, Ligne $ligne, CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findBy(['User' => $this->getUser()]);
        $form = $this->createForm(
            LigneType::class,
            $ligne,
            array(
                'date' => $ligne->getDate(),
                'categories' => $categories,
                'type' => $ligne->getType(),
                'statut' => $ligne->getStatut(),
                'user_id' => $this->getUser()->getId(),
                'shared_with' => $ligne->getOwner(),

            )
        );
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

        return $this->redirectToRoute('home_redirect', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/share/user',name:'line_share')]
    public function share(Request $request,LigneRepository $ligneRepository,UserRepository $userRepository){
        
        $lines = $request->request->get('entry');     
        $lines = $ligneRepository->findBy(['id'=>$lines]);   

        $users = $request->request->get('users');
        if($users != null){
            $users = $userRepository->findBy(['id'=>$users]);

            foreach($lines as $line){
                foreach($users as $user){
                    $line->addOwner($user);
                }
            }

            $entityManager = $this->getDoctrine()->getManager();    
            $entityManager->flush();

            return $this->redirectToRoute('resume');

        }

        return $this->render('ligne/share.html.twig',[
            'users' => $userRepository->findAll(),
            'lines'=> $lines,
        ]);
    }
}
