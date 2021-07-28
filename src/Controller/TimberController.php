<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\Timber\RegistryTimberPdfReport;
use App\Report\Timber\RegistryTimberReport;
use App\Report\Timber\TimberFromPostavPdfReport;
use App\Report\Timber\TimberFromPostavReport;
use App\Report\Timber\TimberPdfReport;
use App\Report\Timber\TimberReport;
use App\Repository\PeopleRepository;
use App\Repository\TimberRepository;
use Tlc\ReportBundle\Controller\AbstractReportController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("report/timber", name: "report_timber_")]
class TimberController extends AbstractReportController
{
    public function __construct(
        PeopleRepository $peopleRepository,
        private TimberRepository $timberRepository
    ) {
        parent::__construct($peopleRepository);
    }

    #[Route("/pdf", name: "show_pdf")]
    public function showReportPdf()
    {
        $report = new TimberReport($this->period, $this->timberRepository, $this->peoples, $this->sqlWhere);
        $pdf = new TimberPdfReport($report);
        $pdf->render();
    }

    #[Route("_postav/pdf", name: "from_postav_show_pdf")]
    public function showReportFromPostavPdf()
    {
        $report = new TimberFromPostavReport($this->period, $this->timberRepository, $this->peoples, $this->sqlWhere);
        $pdf = new TimberFromPostavPdfReport($report);
        $pdf->render();
    }

    #[Route("_registry/pdf", name: "from_registry_show_pdf")]
    public function showReportFromRegistryPdf()
    {
        $report = new RegistryTimberReport($this->period, $this->timberRepository, $this->peoples, $this->sqlWhere);
        $pdf = new RegistryTimberPdfReport($report);
        $pdf->render();
    }
}
