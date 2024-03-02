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
        $year = $request->query->get('year') != null ? $request->query->get('year') : date('Y');
        $unclosed_entry = $ligneRepository->findUnclosedEntries();
        $months = [];

        foreach($unclosed_entry as $entry){
            $months_id = $entry->getDate()->format('m');
            $months[$months_id]["entry"][] = $entry;
            $months[$months_id]["name"] = $entry->getDate()->format('F');
            $months[$months_id]["id"] = $entry->getDate()->format('m');
            if(isset($months[$months_id]["amount"]))
                $months[$months_id]["amount"] += $entry->getAmount();
            else
                $months[$months_id]["amount"] = $entry->getAmount();

            if(!$entry->getClosed())
                $months[$months_id]["closed"] = false;


        }

        


        
        

        return $this->render('board/index.html.twig', [
            'months' => $months,
            'year' => $year,
            
        ]);
    }

    #[Route('/board/{year}/{month}', name: 'app_month_board')]
    public function month_board(Request $request,int $year,string $month,LigneRepository $ligneRepository)
    {
     

        $income = $ligneRepository->getIncomeByMonth($month, $year, $this->getUser());
        $expense = $ligneRepository->getExpenseByMonth($month, $year, $this->getUser());

        $shares = $ligneRepository->findSharesByMonth($month, $year, $this->getUser());


        $shares_by_user = [];
        $sum = 0;

        foreach ($shares as $share) {
            $userId = $share['user_id'];
            $sum += $share['amount'];
            
            $owners = $ligneRepository->find($share['id'])->getOwner();
            foreach($owners as $owner){
                $ownerId = $owner->getId();
                if(!isset($shares_by_user[$ownerId]))
                    $shares_by_user[$ownerId] = [
                        'user' => $owner->__toString(),
                        'amount' => 0
                    ];
            }

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

        return $this->render('board/month_view.html.twig',[
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
