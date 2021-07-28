<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\Board\BoardFromPostavPdfReport;
use App\Report\Board\BoardFromPostavReport;
use App\Repository\PeopleRepository;
use App\Repository\TimberRepository;
use Symfony\Component\Routing\Annotation\Route;
use Tlc\ReportBundle\Controller\AbstractReportController;

#[Route("report/board", name: "report_board_")]
class BoardController extends AbstractReportController
{

    public function __construct(
        PeopleRepository $peopleRepository,
        private TimberRepository $timberRepository
    ) {
        parent::__construct($peopleRepository);
    }

    #[Route("_postav/pdf", name: "from_postav_show_pdf")]
    public function showReportPdf()
    {
        $report = new BoardFromPostavReport($this->period, $this->timberRepository, $this->peoples, $this->sqlWhere);
        $pdf = new BoardFromPostavPdfReport($report);
        $pdf->render();
    }
}
