<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\AbstractReport;
use App\Report\Event\ActionOperatorEventReport;
use App\Report\Event\EventPdfReport;
use App\Repository\EventRepository;
use App\Repository\PeopleRepository;
use App\Repository\ShiftRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("report/event/action_operator", name="report_event_action_operator_")
 */
class ActionOperatorEventController extends AbstractController
{
    /**
     * @Route("/{start}...{end}/people/{idsPeople}/pdf", name="for_period_show_pdf")
     */
    public function showReportForPeriodPdf(string $start, string $end, string $idsPeople, PeopleRepository $peopleRepository, EventRepository $repository)
    {
        $idsPeople = explode('...', $idsPeople);
        foreach ($idsPeople as $idPeople) {
            $peoples[] = $peopleRepository->find($idPeople);
        }
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $report = new ActionOperatorEventReport($period, $repository, $peoples);
        $this->showPdf($report);
    }

    // /**
    //  * @Route("/shift/{start}/pdf", name="for_shift_show_pdf")
    //  */
    // public function showReportForShiftPdf(string $start, EventRepository $repository, ShiftRepository $shiftRepository)
    // {
    //     $shift = $shiftRepository->find($start);
    //     $report = new ActionOperatorEventReport($shift->getPeriod(), $repository, $shift);
    //     $this->showPdf($report);
    // }

    private function showPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new EventPdfReport($report);
        $pdf->render();
    }
}
