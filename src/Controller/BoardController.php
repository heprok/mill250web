<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\AbstractReport;
use App\Report\Board\BoardFromPostavPdfReport;
use App\Report\Board\BoardFromPostavReport;
use App\Repository\PeopleRepository;
use App\Repository\ShiftRepository;
use App\Repository\TimberRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("report/board", name="report_board_")
 */
class BoardController extends AbstractController
{
    private PeopleRepository $peopleRepository;
    private TimberRepository $timberRepository;

    public function __construct(PeopleRepository $peopleRepository, TimberRepository $timberRepository)
    {
        $this->peopleRepository = $peopleRepository;
        $this->timberRepository = $timberRepository;
    }

    /**
     * @Route("_postav/{start}...{end}/people/{idsPeople}/pdf", name="from_postav_for_period_with_people_show_pdf")
     */
    public function showReportForPeriodWithPeoplePdf(string $start, string $end, string $idsPeople)
    {
        $request = Request::createFromGlobals();
        $sqlWhere = json_decode($request->query->get('sqlWhere'));
        
        $idsPeople = explode('...', $idsPeople);
        $peoples = [];
        foreach ($idsPeople as $idPeople) {
            if($idPeople != '')
                $peoples[] = $this->peopleRepository->find($idPeople);
        }
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $report = new BoardFromPostavReport($period, $this->timberRepository, $peoples, $sqlWhere);
        $this->showPdf($report);
    }    
    
    /**
     * @Route("_postav/{start}...{end}/pdf", name="from_postav_for_period_show_pdf")
     */
    public function showReportForPeriodPdf(string $start, string $end)
    {
        $this->showReportForPeriodWithPeoplePdf($start, $end, '');
    }

    private function showPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new BoardFromPostavPdfReport($report);
        $pdf->render();
    }
}
