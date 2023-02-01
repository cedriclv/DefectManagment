<?php

namespace App\Controller;

use App\Repository\DefectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefectController extends AbstractController
{
    #[Route('/defect', name: 'app_defect')]
    public function index(DefectRepository $defectRepository ): Response
    {
        $defects = $defectRepository->findAll();
        return $this->render('defect/index.html.twig', [
            'defects' => $defects,
        ]);
    }
}
