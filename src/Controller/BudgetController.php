<?php

namespace App\Controller;

use App\Repository\BankAccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BudgetController extends AbstractController
{
    #[Route('/budget', name: 'app_budget')]
    public function index(): Response
    {
        return $this->render('panel/budget/index.html.twig', [
            'controller_name' => 'BudgetController',
        ]);
    }

    #[Route('/budget/account/', name: 'app_budget_by_bank_account')]
    public function by_bank_account(Request $request,BankAccountRepository $bankAccountRepository): Response
    {
        $account_id = $request->query->get('account_id');
        if(!$account_id && !$this->getUser()->hasBankAccount()){
            $this->addFlash('error','Veuillez créer un compte');
            return $this->redirectToRoute('app_budget');
        }
        if(!$account_id){
            $account_id = $this->getUser()->getBankAccounts()[0]->getId();
        }

        $bankAccount = $bankAccountRepository->find($account_id);

        return $this->render('panel/budget/account_overview.html.twig', [
            'controller_name' => 'BudgetController',
            'current_bank_account' => $bankAccount,
        ]);
    }
}
