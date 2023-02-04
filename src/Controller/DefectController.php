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
        $numberDefectPerDateReason = $defectRepository->findDefectsPerCount();
        //dd($numberDefectPerDateReason);

        $col = array_column($numberDefectPerDateReason, "date");
        array_multisort($col, SORT_ASC, $numberDefectPerDateReason);
        $mondaylasteek = new DateTime('last Monday - 7 days');

        $numberDefectPerWeek = [];

        //get all reasons
        //get teh tab will all reasons and remove duplicate
        $reasons = [];
        for ($i = 0; $i < count($numberDefectPerDateReason); $i++) {
            $reasons[] = $numberDefectPerDateReason[$i]['reason'];
        }
        $reasons = array_values(array_unique($reasons));
       // dd($reasons);
        //get all dates
        //get teh tab will all dates and remove duplicate
        $weeks = [];
        for ($i = 0; $i < count($numberDefectPerDateReason); $i++) {
            $week = (int) $numberDefectPerDateReason[$i]['date']->format('W');
            if (!in_array($week, $weeks)) {
                $weeks[] = $week;
            }
        }
        $weeks = array_unique($weeks);
        // creer les datasets vierges            
        $datas = []; //[$reasons1, $reasons2, $reasons3, $reasons4, $reasons5, $reasons6];
        //for each reasons get the dataset defect per week
        for ($j = 0; $j < count($reasons); $j++) {
            for ($i = 0; $i < count($numberDefectPerDateReason); $i++) {
                if ($numberDefectPerDateReason[$i]['reason'] === $reasons[$j]) {
                    $datas[$j][] = $numberDefectPerDateReason[$i]['defectNumber'];
                }
            }
        }
        $colors = ['#27b89b ', '#484848', 'violet', 'green', 'yellow', 'grey', 'cyan'];
        // chart Full Year
        $chartFullYear = $chartBuilder->createChart(Chart::TYPE_LINE);
        $datasets = [];
        for ($i=0; $i < count($reasons); $i++) {
            $datasets[] = [
                'label' => $reasons[$i],
                'backgroundColor' => $colors[$i],
                'borderColor' => 'lightgrey',
                'data' => $datas[$i],
                'fill' => true,
            ];
        }    
/*        $datasets = [
            [
                'label' => $reasons[0],
                'backgroundColor' => $colors[0],
                'borderColor' => '#1d7634',
                'data' => $datas[0],
                'fill' => true,
            ],
            [
                'label' => $reasons[1],
                'backgroundColor' => $colors[1],
                'borderColor' => '#b1ff57',
                'data' => $datas[1],
                'fill' => true,
            ],
        ];
  */      $chartFullYear->setData([
            'labels' => $weeks,
            'datasets' => $datasets,
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
                'legend' => [
                    'display' => true,
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'stacked' => true,
                    'ticks' => [
                        'font' => [
                            'size' => 20,
                        ]
                    ],
                ],
                'y' => [
                    'stacked' => true,
                    'ticks' => [
                        'font' => [
                            'size' => 20,
                        ]
                    ],
                    'suggestedMin' => 200,
                    'suggestedMax' => 400,
                ],
            ],
        ]);

        $numberDefectPerReason = $defectRepository->findDefectsPerReason($mondaylasteek);
        //dd($numberDefectPerReason);
        // chart BreakDown
        $chartBreakDown = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $lastWeekDefectReasons = [];
        $lastWeekDefectQuantityPerReason = [];
        $numberDefectLastWeek = 0;
        //avoir les labels par semaine
        $j = 0;
        for ($i = 0; $i < count($numberDefectPerReason); $i++) {
            if ($numberDefectPerReason[$i]['isInvestigated'] === true) {
                $lastWeekDefectReasons[$j] = $numberDefectPerReason[$i]['reason'];
                $lastWeekDefectQuantityPerReason[$j] = $numberDefectPerReason[$i]['defectNumber'];
                $j++;
            }
            $numberDefectLastWeek += $numberDefectPerReason[$i]['defectNumber'];
        }

        $chartBreakDown->setData([
            'labels' => $lastWeekDefectReasons,
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
                    'align' => 'start',
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