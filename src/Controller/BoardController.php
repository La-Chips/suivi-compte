<?php

namespace App\Controller;

use App\Repository\LigneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoardController extends AbstractController
{
    #[Route('/board', name: 'app_board')]
    public function index(LigneRepository $ligneRepository): Response
    {

        $income = $ligneRepository->getIncomeByMonth(date('m'), date('Y'));

        return $this->render('board/index.html.twig', [
            'balance'=> [
                'incomes' => $income,
                'expenses' => 0,
                'total' => $income - 0
            ]
        ]);
    }
}
