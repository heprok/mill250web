<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\AbstractReport;
use App\Report\Downtime\DowntimePdfReport;
use App\Report\Downtime\DowntimeReport;
use App\Repository\DowntimeRepository;
use App\Repository\PeopleRepository;
use App\Repository\ShiftRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("report/downtimes", name="report_downtimes_")
 */
class DowntimeController extends AbstractController
{
    /**
     * @Route("/{start}...{end}/people/{idsPeople}/pdf", name="for_period_show_pdf")
     */
    public function showReportForPeriodPdf(string $start, string $end, string $idsPeople, PeopleRepository $peopleRepository, DowntimeRepository $repository)
    {
        $idsPeople = explode('...', $idsPeople);
        foreach ($idsPeople as $idPeople) {
            $peoples[] = $peopleRepository->find($idPeople);
        }
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $report = new DowntimeReport($period, $repository, $peoples);
        $this->showPdf($report);
    }

    // /**
    //  * @Route("/shift/{start}/pdf", name="for_shift_show_pdf")
    //  */
    // public function showReportForShiftPdf(string $start, DowntimeRepository $repository, ShiftRepository $shiftRepository)
    // {
    //     $shift = $shiftRepository->find($start);
    //     $report = new DowntimeReport($shift->getPeriod(), $repository, $shift);
    //     $this->showPdf($report);
    // }

    private function showPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new DowntimePdfReport($report);
        $pdf->render();
    }
}
