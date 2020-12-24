<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\AbstractReport;
use App\Report\Event\EventPdfReport;
use App\Report\Event\AlertEventReport;
use App\Repository\EventRepository;
use App\Repository\ShiftRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("report/event/alert", name="report_event_alert_")
 */
class AlertEventController extends AbstractController
{
    /**
     * @Route("/{start}...{end}/pdf", name="for_period_show_pdf")
     */
    public function showReportForPeriodPdf(string $start, string $end, EventRepository $repository)
    {
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $report = new AlertEventReport($period, $repository);
        $this->showPdf($report);
    }

    /**
     * @Route("/shift/{start}/pdf", name="for_shift_show_pdf")
     */
    public function showReportForShiftPdf(string $start, EventRepository $repository, ShiftRepository $shiftRepository)
    {
        $shift = $shiftRepository->find($start);
        $report = new AlertEventReport($shift->getPeriod(), $repository, $shift);
        $this->showPdf($report);
    }

    private function showPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new EventPdfReport($report);
        $pdf->render();
    }
}
