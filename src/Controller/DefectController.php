<?php

namespace App\Controller;

use App\Entity\Defect;
use App\Form\DefectType;
use App\Repository\DefectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/defect')]
class DefectController extends AbstractController
{
    #[Route('/', name: 'app_defect_index', methods: ['GET'])]
    public function index(DefectRepository $defectRepository): Response
    {
        return $this->render('defect/index.html.twig', [
            'defects' => $defectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_defect_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DefectRepository $defectRepository): Response
    {
        $defect = new Defect();
        $form = $this->createForm(DefectType::class, $defect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $defectRepository->save($defect, true);

            return $this->redirectToRoute('app_defect_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('defect/new.html.twig', [
            'defect' => $defect,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_defect_show', methods: ['GET'])]
    public function show(Defect $defect): Response
    {
        return $this->render('defect/show.html.twig', [
            'defect' => $defect,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_defect_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Defect $defect, DefectRepository $defectRepository): Response
    {
        $form = $this->createForm(DefectType::class, $defect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $defectRepository->save($defect, true);

            return $this->redirectToRoute('app_defect_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('defect/edit.html.twig', [
            'defect' => $defect,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_defect_delete', methods: ['POST'])]
    public function delete(Request $request, Defect $defect, DefectRepository $defectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$defect->getId(), $request->request->get('_token'))) {
            $defectRepository->remove($defect, true);
        }

        return $this->redirectToRoute('app_defect_index', [], Response::HTTP_SEE_OTHER);
    }
}
