<?php

namespace App\Controller;

use App\Repository\LigneRepository;
use PhpParser\Builder\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/render')]
class RenderController extends AbstractController
{
    #[Route('/table/lines', name: 'render_table_lines',methods: ['POST'])]
    public function tables_lines(Request $request,LigneRepository $ligneRepository): Response
    {
        $params = json_decode($request->getContent(), true);
        $limit = $params['unlimited'] == 0 ? 100 : null;
        $lines =$ligneRepository->findByFilter($params,limit: $limit);

        $sum = 0;
        foreach($lines as $line){
            $sum += $line->getMontant();
        }



        return $this->render('templates/tables_lignes.html.twig', [
            'lignes' => $lines,
            'sort' => $params['sort'],
            'order' => $params['order'],
            'sum' => $sum,
        ]);
    }
}
