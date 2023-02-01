<?php

namespace App\Controller;

use App\Repository\CountRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
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
    #[Route('/monitor', name: 'app_defect_monitor', methods: ['GET'])]
    public function monitor(ChartBuilderInterface $chartBuilder, DefectRepository $defectRepository, CountRepository $countRepository): Response
    {
        $numberDefectPerDate = $defectRepository->findDefectsPerCount();
        $col = array_column( $numberDefectPerDate, "date" );
        array_multisort( $col, SORT_ASC, $numberDefectPerDate );

        $numberDefectPerWeek = [];
        for ($i=0; $i < count($numberDefectPerDate); $i++) { 
            $numberDefectPerWeek[$i]['year'] = (int) $numberDefectPerDate[$i]['date']->format('Y');
            $numberDefectPerWeek[$i]['month'] = (int) $numberDefectPerDate[$i]['date']->format('m');
            $numberDefectPerWeek[$i]['week'] = (int) $numberDefectPerDate[$i]['date']->format('W');
            $numberDefectPerWeek[$i]['defectNumber'] = $numberDefectPerDate[$i]['defectNumber'];
        }   
        // chart Full Year
        $chartFullYear = $chartBuilder->createChart(Chart::TYPE_LINE);
        
        //avoir les labels par semaine
        for ($i=0; $i < count($numberDefectPerWeek); $i++) { 
            $labelsFullYear[$i] = $numberDefectPerWeek[$i]['week'];
            $dataFullYear[$i] = $numberDefectPerWeek[$i]['defectNumber'];
        }
//        dd($dataFullYear);
        
        $chartFullYear->setData([
            'labels' => $labelsFullYear,
            'datasets' => [
                [
                    'label' => 'FULL YEAR RESULTS',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $dataFullYear,
                ],
            ],
        ]);

        $chartFullYear->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        // chart BreakDown
        $chartBreakDown = $chartBuilder->createChart(Chart::TYPE_LINE);
        $lastWeekDefectReasons = [];
        $lastWeekDefectQuantityPerReason = [];
        $chartBreakDown->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'DEFECTS PER REASON',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chartBreakDown->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);


        return $this->render('defect/monitor.html.twig', [
            'chartBreakDown' => $chartBreakDown,
            'chartFullYear' => $chartFullYear,
            'defects' => $defectRepository->findAll(),            
        ]);
    }

    #[Route('/', name: 'app_defect_index', methods: ['GET'])]
    public function index(DefectRepository $defectRepository): Response
    {
        return $this->render('defect/index.html.twig', [
            'defects' => $defectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_defect_new', methods: ['GET', 'POST'])]
    public function new (Request $request, DefectRepository $defectRepository): Response
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
        if ($this->isCsrfTokenValid('delete' . $defect->getId(), $request->request->get('_token'))) {
            $defectRepository->remove($defect, true);
        }

        return $this->redirectToRoute('app_defect_index', [], Response::HTTP_SEE_OTHER);
    }
}