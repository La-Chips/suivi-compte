<?php

namespace App\Controller;

use App\Repository\LigneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoardController extends AbstractController
{
    #[Route('/board', name: 'app_board')]
    public function index(Request $request,LigneRepository $ligneRepository): Response
    {
        $request->query->get('month') != null ? $month = $request->query->get('month') : $month = date('F');
        $request->query->get('year') != null ? $year = $request->query->get('year') : $year = date('Y');
     

        $income = $ligneRepository->getIncomeByMonth($month, $year, $this->getUser());
        $expense = $ligneRepository->getExpenseByMonth($month, $year, $this->getUser());

        $shares = $ligneRepository->findSharesByMonth($month, $year, $this->getUser());


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
            'month' => $month,
            'year' => $year,
        ]);
    }

    // Profile route
    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('board/profile.html.twig', [
            'controller_name' => 'BoardController',
        ]);
    }
}
