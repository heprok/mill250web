<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\AbstractReport;
use App\Report\Board\BoardFromPostavPdfReport;
use App\Report\Board\BoardFromPostavReport;
use App\Repository\ShiftRepository;
use App\Repository\TimberRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("report/board", name="report_board_")
 */
class BoardController extends AbstractController
{
    /**
     * @Route("_postav/{start}...{end}/pdf", name="from_postav_for_period_show_pdf")
     */
    public function showReportForPeriodPdf(string $start, string $end, TimberRepository $repository)
    {
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $report = new BoardFromPostavReport($period, $repository);
        $this->showPdf($report);
    }

    /**
     * @Route("_postav/shift/{start}/pdf", name="from_postav_for_shift_show_pdf")
     */
    public function showReportForShiftPdf(string $start, TimberRepository $repository, ShiftRepository $shiftRepository)
    {
        $shift = $shiftRepository->find($start);
        $report = new BoardFromPostavReport($shift->getPeriod(), $repository, $shift);
        $this->showPdf($report);
    }

    private function showPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new BoardFromPostavPdfReport($report);
        $pdf->render();
    }
}
