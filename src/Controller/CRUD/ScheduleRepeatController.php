<?php

namespace App\Controller\CRUD;

use App\Entity\ScheduleExpense;
use App\Entity\ScheduleRepeat;
use App\Form\ScheduleRepeatType;
use App\Repository\ScheduleRepeatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/schedule/repeat')]
class ScheduleRepeatController extends AbstractController
{
    #[Route('/', name: 'app_schedule_repeat_index', methods: ['GET'])]
    public function index(ScheduleRepeatRepository $scheduleRepeatRepository): Response
    {
        return $this->render('crud/schedule_repeat/index.html.twig', [
            'schedule_repeats' => $scheduleRepeatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_schedule_repeat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ScheduleRepeatRepository $scheduleRepeatRepository): Response
    {
        $scheduleRepeat = new ScheduleRepeat();
        $form = $this->createForm(ScheduleRepeatType::class, $scheduleRepeat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $scheduleRepeatRepository->add($scheduleRepeat);
            return $this->redirectToRoute('app_schedule_repeat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/schedule_repeat/new.html.twig', [
            'schedule_repeat' => $scheduleRepeat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_schedule_repeat_show', methods: ['GET'])]
    public function show(ScheduleRepeat $scheduleRepeat): Response
    {
        return $this->render('crud/schedule_repeat/show.html.twig', [
            'schedule_repeat' => $scheduleRepeat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_schedule_repeat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ScheduleRepeat $scheduleRepeat, ScheduleRepeatRepository $scheduleRepeatRepository): Response
    {
        $form = $this->createForm(ScheduleRepeatType::class, $scheduleRepeat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $scheduleRepeatRepository->add($scheduleRepeat);
            return $this->redirectToRoute('app_schedule_repeat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/schedule_repeat/edit.html.twig', [
            'schedule_repeat' => $scheduleRepeat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_schedule_repeat_delete', methods: ['POST'])]
    public function delete(Request $request, ScheduleRepeat $scheduleRepeat, ScheduleRepeatRepository $scheduleRepeatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$scheduleRepeat->getId(), $request->request->get('_token'))) {
            $scheduleRepeatRepository->remove($scheduleRepeat);
        }

        return $this->redirectToRoute('app_schedule_repeat_index', [], Response::HTTP_SEE_OTHER);
    }


    
}
