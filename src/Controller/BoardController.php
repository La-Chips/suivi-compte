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

     

        $income = $ligneRepository->getIncomeByMonth(date('F'), date('Y'), $this->getUser());
        $expense = $ligneRepository->getExpenseByMonth(date('F'), date('Y'), $this->getUser());

        $shares = $ligneRepository->findSharesByMonth(date('F'), date('Y'), $this->getUser());


        $shares_by_user = [];
        $sum = 0;

        foreach ($shares as $share) {
            $userId = $share['user_id'];
            $sum += $share['amount'];
            if(isset($shares_by_user[$userId])) {
                $shares_by_user[$userId]['amount'] += $share['amount'];
            } else {
                $shares_by_user[$userId] = [
                    'user' => $share['username'],
                    'amount' => $share['amount']
                ];
            }
        }

        foreach ($shares_by_user as $key => $value) {
            $shares_by_user[$key]['shares'] = $sum / count($shares_by_user);
        }


  
        

        return $this->render('board/index.html.twig', [
            'balance'=> [
                'incomes' => $income,
                'expenses' => $expense,
                'total' => $income + $expense
            ],
            'shares' => $shares,
            'sharesByUser' => $shares_by_user,
        ]);
    }
}
