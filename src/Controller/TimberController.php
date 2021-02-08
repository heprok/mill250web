<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\AbstractReport;
use App\Report\Timber\TimberFromPostavPdfReport;
use App\Report\Timber\TimberFromPostavReport;
use App\Report\Timber\TimberPdfReport;
use App\Report\Timber\TimberReport;
use App\Repository\PeopleRepository;
use App\Repository\ShiftRepository;
use App\Repository\TimberRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("report/timber", name="report_timber_")
 */
class TimberController extends AbstractController
{
    private PeopleRepository $peopleRepository;
    private TimberRepository $timberRepository;

    public function __construct(PeopleRepository $peopleRepository, TimberRepository $timberRepository)
    {
        $this->peopleRepository = $peopleRepository;
        $this->timberRepository = $timberRepository;
    }

    /**
     * @Route("/{start}...{end}/people/{idsPeople}/pdf", name="for_period_with_people_show_pdf")
     */
    public function showReportForPeriodWithPeoplePdf(string $start, string $end, string $idsPeople)
    {
        $idsPeople = explode('...', $idsPeople);
        $peoples = [];
        foreach ($idsPeople as $idPeople) {
            if($idPeople != '')
                $peoples[] = $this->peopleRepository->find($idPeople);
        }
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $report = new TimberReport($period, $this->timberRepository, $peoples);
        $this->showPdf($report);
    }    
    
    /**
     * @Route("/{start}...{end}/pdf", name="for_period_show_pdf")
     */
    public function showReportForPeriodPdf(string $start, string $end)
    {
        $this->showReportForPeriodWithPeoplePdf($start, $end, '');
    }
    
    private function showPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new TimberPdfReport($report);
        $pdf->render();
    }

    /**
     * @Route("_postav/{start}...{end}/people/{idsPeople}/pdf", name="from_postav_for_period_with_people_show_pdf")
     */
    public function showReportFromPostavForPeriodWithPeoplePdf(string $start, string $end, string $idsPeople)
    {
        $idsPeople = explode('...', $idsPeople);
        $peoples = [];
        foreach ($idsPeople as $idPeople) {
            if($idPeople != '')
                $peoples[] = $this->peopleRepository->find($idPeople);
        }
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $report = new TimberFromPostavReport($period, $this->timberRepository, $peoples);
        $this->showPostavPdf($report);
    }    
    
    /**
     * @Route("_postav/{start}...{end}/pdf", name="from_postav_for_period_show_pdf")
     */
    public function showReportFromPostavForPeriodPdf(string $start, string $end)
    {
        $this->showReportFromPostavForPeriodWithPeoplePdf($start, $end, '');
    }

    private function showPostavPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new TimberFromPostavPdfReport($report);
        $pdf->render();
    }
}
