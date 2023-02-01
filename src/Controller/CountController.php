<?php

namespace App\Controller;

use App\Entity\Count;
use App\Form\CountType;
use App\Repository\CountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/count')]
class CountController extends AbstractController
{
    #[Route('/', name: 'app_count_index', methods: ['GET'])]
    public function index(CountRepository $countRepository): Response
    {
        return $this->render('count/index.html.twig', [
            'counts' => $countRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_count_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CountRepository $countRepository): Response
    {
        $count = new Count();
        $form = $this->createForm(CountType::class, $count);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countRepository->save($count, true);

            return $this->redirectToRoute('app_count_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('count/new.html.twig', [
            'count' => $count,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_count_show', methods: ['GET'])]
    public function show(Count $count): Response
    {
        return $this->render('count/show.html.twig', [
            'count' => $count,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_count_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Count $count, CountRepository $countRepository): Response
    {
        $form = $this->createForm(CountType::class, $count);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countRepository->save($count, true);

            return $this->redirectToRoute('app_count_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('count/edit.html.twig', [
            'count' => $count,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_count_delete', methods: ['POST'])]
    public function delete(Request $request, Count $count, CountRepository $countRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$count->getId(), $request->request->get('_token'))) {
            $countRepository->remove($count, true);
        }

        return $this->redirectToRoute('app_count_index', [], Response::HTTP_SEE_OTHER);
    }
}
