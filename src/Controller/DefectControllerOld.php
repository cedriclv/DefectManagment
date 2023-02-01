<?php

namespace App\Controller;

use App\Repository\DefectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefectControllerOld extends AbstractController
{
    #[Route('/defectOld', name: 'app_defectOld')]
    public function index(DefectRepository $defectRepository ): Response
    {
        $defects = $defectRepository->findAll();
        return $this->render('defectOld/index.html.twig', [
            'defects' => $defects,
        ]);
    }
}
