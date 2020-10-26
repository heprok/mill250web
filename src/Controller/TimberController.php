<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\Timber\TimberPdfReport;
use App\Report\Timber\TimberReport;
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
     * @Route("/{start}...{end}/pdf", name="show_pdf")
     */
    public function showPdf(string $start, string $end, TimberRepository $repository)
    {   
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate); 
        $report = new TimberReport($period, $repository);
        $report->init();
        $pdf = new TimberPdfReport($report);
        $pdf->render();
    }
}
