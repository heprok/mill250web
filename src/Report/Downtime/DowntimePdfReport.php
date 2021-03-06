<?php

declare(strict_types=1);

namespace App\Report\Downtime;

use App\Report\AbstractPdf;
use App\Report\AbstractReport;

final class DowntimePdfReport extends AbstractPdf
{
    public function __construct(AbstractReport $actionOperatorEventReport)
    {
        $this->setReport($actionOperatorEventReport);
        parent::__constructor('L');
    }


    protected function getPointFontHeader(): int
    {
        return 6;
    }

    protected function getPointFontText(): int
    {
        return 8;
    }

    protected function getHeightCell(): int
    {
        return 5;
    }
}
