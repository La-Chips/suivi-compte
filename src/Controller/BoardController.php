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
        $own_shares = $this->getUser()->getLignes();

        $shares_data = [];

        foreach ($shares as $share) {
            $userId = $share->getUser()->getId();
            if(isset($shares_data[$userId])) {
                $shares_data[$userId]['amount'] += $share->getMontant();
            } else {
                $shares_data[$userId] = [
                    'user' => $share->getUser()->getUsername(),
                    'amount' => $share->getMontant()
                ];
            }
        }

        foreach ($own_shares as $share) {
            $userId = $share->getUser()->getId();
            if(isset($shares_data[$userId])) {
                $shares_data[$userId]['amount'] += $share->getMontant();
            } else {
                $shares_data[$userId] = [
                    'user' => $share->getUser()->getUsername(),
                    'amount' => $share->getMontant()
                ];
            }

            foreach($share as $user) {
                if(!isset($shares_data[$user->getId()])) {
                    $shares_data[$user->getId()] = [
                        'user' => $user,
                        'amount' => 0
                    ];
                }
            }
        }
        

        return $this->render('board/index.html.twig', [
            'balance'=> [
                'incomes' => $income,
                'expenses' => $expense,
                'total' => $income + $expense
            ],
            'shares' => $shares,
            'shares_data' => $shares_data,
            'own_shares' => $own_shares,
        ]);
    }
}
