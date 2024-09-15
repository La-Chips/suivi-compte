<?php

namespace App\Controller\CRUD;

use App\Entity\Categorie;
use App\Entity\ScheduleExpense;
use App\Form\ScheduleExpenseType;
use App\Repository\LigneRepository;
use App\Repository\ScheduleExpenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/schedule/expense')]
class ScheduleExpenseController extends AbstractController
{
    #[Route('/', name: 'app_schedule_expense_index', methods: ['GET'])]
    public function index(Request $request,ScheduleExpenseRepository $scheduleExpenseRepository): Response
    {
        $sort = $request->query->get('sort') ?? "id";
        $order = $request->query->get('order') ?? "asc";

        return $this->render('crud/schedule_expense/index.html.twig', [
            'schedule_expenses' => $scheduleExpenseRepository->findByUser($this->getUser()->getId(),[
                'sort' => $sort,
                'order' => $order,
            ])

        ]);
    }

    #[Route('/new', name: 'app_schedule_expense_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ScheduleExpenseRepository $scheduleExpenseRepository, LigneRepository $ligneRepository): Response
    {
        $scheduleExpense = new ScheduleExpense();

        if ($request->query->get('expense_id')) {
            $expense = $ligneRepository->find($request->query->get('expense_id'));

            if ($expense->getUser() != $this->getUser()) {
                // return to referer
                return $this->redirect($request->headers->get('referer'));
            }

            $scheduleExpense->setLabel($expense->getLabel());
            $scheduleExpense->setAmount($expense->getAmount());
            $scheduleExpense->setCategory($expense->getCategorie());
            $scheduleExpense->setStartDate($expense->getDate());
        }

        $form = $this->createForm(ScheduleExpenseType::class, $scheduleExpense, [
            'user_id' => $this->getUser()->getId(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($this->check_object($scheduleExpense) != null){
                $this->addFlash('error',$this->check_object($scheduleExpense));
                return $this->redirectToRoute('app_schedule_expense_new', [], Response::HTTP_SEE_OTHER);
            }

            $scheduleExpenseRepository->add($scheduleExpense);
            return $this->redirectToRoute('app_schedule_expense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/schedule_expense/new.html.twig', [
            'schedule_expense' => $scheduleExpense,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_schedule_expense_show', methods: ['GET'])]
    public function show(ScheduleExpense $scheduleExpense): Response
    {
        return $this->render('crud/schedule_expense/show.html.twig', [
            'schedule_expense' => $scheduleExpense,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_schedule_expense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ScheduleExpense $scheduleExpense, ScheduleExpenseRepository $scheduleExpenseRepository): Response
    {
        $form = $this->createForm(ScheduleExpenseType::class, $scheduleExpense, [
            'user_id' => $this->getUser()->getId(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($this->check_object($scheduleExpense) != null){
                $this->addFlash('error',$this->check_object($scheduleExpense));
                return $this->redirectToRoute('app_schedule_expense_edit', ['id' => $scheduleExpense->getId()], Response::HTTP_SEE_OTHER);
            }

            $scheduleExpenseRepository->add($scheduleExpense);
            return $this->redirectToRoute('app_schedule_expense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/schedule_expense/edit.html.twig', [
            'schedule_expense' => $scheduleExpense,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_schedule_expense_delete', methods: ['POST'])]
    public function delete(Request $request, ScheduleExpense $scheduleExpense, ScheduleExpenseRepository $scheduleExpenseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $scheduleExpense->getId(), $request->request->get('_token'))) {
            $scheduleExpenseRepository->remove($scheduleExpense);
        }

        return $this->redirectToRoute('app_schedule_expense_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/category/{id}', name: 'app_schedule_expense_by_category', methods: ['GET'])]
    public function by_category(Request $request, Categorie $category, ScheduleExpenseRepository $scheduleExpenseRepository): Response
    {
        $bank_accounts = [];
        $scheduleExpenses = $scheduleExpenseRepository->findBy([
            'category' => $category->getId(),
        ]);
        foreach ($scheduleExpenses as $scheduleExpense) {
            if (!isset($bank_accounts[$scheduleExpense->getBankAccount()->getLabel()])) {
                $bank_accounts[$scheduleExpense->getBankAccount()->getLabel()] = [
                    'bank_account' => $scheduleExpense->getBankAccount(),
                    'schedule_expenses' => [$scheduleExpense],
                    'balance' => $scheduleExpense->getAmount(),
                ];
            } else {
                $bank_accounts[$scheduleExpense->getBankAccount()->getLabel()]['schedule_expenses'][] = $scheduleExpense;
                $bank_accounts[$scheduleExpense->getBankAccount()->getLabel()]['balance'] += $scheduleExpense->getAmount();
            }
        }


        return $this->render('crud/schedule_expense/by_category.html.twig', [

            'category' => $category,
            'bank_accounts' => $bank_accounts,

        ]);
    }

    private function check_object(ScheduleExpense $scheduleExpense): ?string
    {

        if($scheduleExpense->getRepeatable()
        && $scheduleExpense->getScheduleRepeat() == null)
            return "Une répétition doit être programmée";
            
        return null;
    }
}
