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
    /**
     * @Route("/{start}...{end}/people/{idsPeople}/pdf", name="for_period_show_pdf")
     */
    public function showReportForPeriodPdf(string $start, string $end, string $idsPeople, PeopleRepository $peopleRepository, TimberRepository $repository)
    {
        $idsPeople = explode('...', $idsPeople);
        foreach ($idsPeople as $idPeople) {
            $peoples[] = $peopleRepository->find($idPeople);
        }
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $report = new TimberReport($period, $repository, $peoples);
        $this->showPdf($report);
    }

    // /**
    //  * @Route("/shift/{start}/pdf", name="for_shift_show_pdf")
    //  */
    // public function showReportForShiftPdf(string $start, TimberRepository $repository, ShiftRepository $shiftRepository)
    // {
    //     $shift = $shiftRepository->find($start);
    //     $report = new TimberReport($shift->getPeriod(), $repository, $shift);
    //     $this->showPdf($report);
    // }

    private function showPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new TimberPdfReport($report);
        $pdf->render();
    }

    /**
     * @Route("_postav/{start}...{end}/people/{idsPeople}/pdf", name="from_postav_for_period_show_pdf")
     */
    public function showReportFromPostavForPeriodPdf(string $start, string $end, string $idsPeople, PeopleRepository $peopleRepository, TimberRepository $repository)
    {
        $idsPeople = explode('...', $idsPeople);
        foreach ($idsPeople as $idPeople) {
            $peoples[] = $peopleRepository->find($idPeople);
        }
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $report = new TimberFromPostavReport($period, $repository, $peoples);
        $this->showPostavPdf($report);
    }

    // /**
    //  * @Route("_postav/shift/{start}/pdf", name="from_postav_for_shift_show_pdf")
    //  */
    // public function showReportFromPostavForShiftPdf(string $start, TimberRepository $repository, ShiftRepository $shiftRepository)
    // {
    //     $shift = $shiftRepository->find($start);
    //     $report = new TimberFromPostavReport($shift->getPeriod(), $repository, $shift);
    //     $this->showPostavPdf($report);
    // }

    private function showPostavPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new TimberFromPostavPdfReport($report);
        $pdf->render();
    }
}
