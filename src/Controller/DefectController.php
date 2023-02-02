<?php

namespace App\Controller;

use DateTime;
use App\Entity\Defect;
use App\Form\DefectType;
use App\Repository\CountRepository;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\DefectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/defect')]
class DefectController extends AbstractController
{
    #[Route('/monitor', name: 'app_defect_monitor', methods: ['GET'])]
    public function monitor(ChartBuilderInterface $chartBuilder, DefectRepository $defectRepository, CountRepository $countRepository): Response
    {
        $numberDefectPerDate = $defectRepository->findDefectsPerCount();

        $col = array_column($numberDefectPerDate, "date");
        array_multisort($col, SORT_ASC, $numberDefectPerDate);
        $mondaylasteek = new DateTime('last Monday - 7 days');

        $numberDefectPerWeek = [];
        for ($i = 0; $i < count($numberDefectPerDate); $i++) {
            $numberDefectPerWeek[$i]['year'] = (int) $numberDefectPerDate[$i]['date']->format('Y');
            $numberDefectPerWeek[$i]['month'] = (int) $numberDefectPerDate[$i]['date']->format('m');
            $numberDefectPerWeek[$i]['week'] = (int) $numberDefectPerDate[$i]['date']->format('W');
            $numberDefectPerWeek[$i]['defectNumber'] = $numberDefectPerDate[$i]['defectNumber'];
        }
        // chart Full Year
        $chartFullYear = $chartBuilder->createChart(Chart::TYPE_BAR);

        //avoir les labels par semaine
        for ($i = 0; $i < count($numberDefectPerWeek); $i++) {
            $labelsFullYear[$i] = $numberDefectPerWeek[$i]['week'];
            $dataFullYear[$i] = $numberDefectPerWeek[$i]['defectNumber'];
        }
        //        dd($dataFullYear);

        $chartFullYear->setData([
            'labels' => $labelsFullYear,
            'datasets' => [
                [
                    'label' => 'FULL YEAR RESULTS',
                    'backgroundColor' => '#32ac71',
                    'borderColor' => '#1d7634',
                    'data' => $dataFullYear,
                ],
            ],
        ]);

        $chartFullYear->setOptions([
            'plugins' => [
                'title' => [
                    'display' => true,
                    'color' => '#484848',
                    'text' => 'Defect per Weeks',
                    'padding' => [
                        'top' => 10,
                        'bottom' => 30
                    ],
                    'font' => [
                        'size' => 22,
                    ]
                ],
                'legend' => 'false',
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'suggestedMin' => 200,
                    'suggestedMax' => 400,
                ],
            ],
        ]);

        $numberDefectPerReason = $defectRepository->findDefectsPerReason($mondaylasteek);

        // chart BreakDown
        $chartBreakDown = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $lastWeekDefectReasons = [];
        $lastWeekDefectQuantityPerReason = [];
        $numberDefectLastWeek = 0;
        //avoir les labels par semaine
        for ($i = 0; $i < count($numberDefectPerReason); $i++) {
            $lastWeekDefectReasons[$i] = $numberDefectPerReason[$i]['reason'];
            $lastWeekDefectQuantityPerReason[$i] = $numberDefectPerReason[$i]['defectNumber'];
            $numberDefectLastWeek += $numberDefectPerReason[$i]['defectNumber'];
        }


        $chartBreakDown->setData([
            'labels' => $lastWeekDefectReasons,
            //['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'DEFECTS PER REASON',
                    'backgroundColor' => [
                        "#0B8142",
                        "#B2FF59",
                        "#32AC71",
                        "#AED580",
                        "#2E8B57"
                    ],
                    'borderColor' => '0B552B',
                    'data' => $lastWeekDefectQuantityPerReason,
                    //[0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chartBreakDown->setOptions([
            'plugins' => [
                'title' => [
                    'display' => true,
                    'color' => '#484848',
                    'text' => 'Defect Reasons',
                    'padding' => [
                        'top' => 10,
                        'bottom' => 30
                    ],
                    'font' => [
                        'size' => 22,
                    ]
                ],
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'font' => [
                            'size' => 14,
                            'color' => 'black'
                        ]
                    ]
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'display' => false,
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'display' => false,
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                    'grid' => [
                        'display' => false,
                    ],
                ],

            ],
        ]);


        return $this->render('defect/monitor.html.twig', [
            'chartBreakDown' => $chartBreakDown,
            'chartFullYear' => $chartFullYear,
            'defects' => $defectRepository->findAll(),
            'numberDefectLastWeek' => $numberDefectLastWeek,
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