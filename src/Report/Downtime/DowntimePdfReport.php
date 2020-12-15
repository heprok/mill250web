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
        parent::__constructor();
    }


    protected function getPointFontHeader(): int
    {
        return 6;
    }

    protected function getColumnInPrecent(): array
    {
        return [5, 15, 15, 24, 24, 20];
    }

    protected function getAlignForColumns(): array
    {
        return ['C', 'C', 'C', 'C', 'C', 'C', 'R', 'R'];
    }

    protected function getPointFontText(): int
    {
        return 8;
    }

    protected function getHeightCell(): int
    {
        return 5;
    }

    public function render()
    {
        return $this->Output($this->getNameFile());
    }
}
