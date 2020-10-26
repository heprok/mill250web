<?php

declare(strict_types=1);

namespace App\Report\Timber;

use App\Report\AbstractPdf;
use App\Report\AbstractReport;

final class TimberPdfReport extends AbstractPdf
{
    public function __construct(AbstractReport $actionOperatorEventReport)
    {
        $this->setReport($actionOperatorEventReport);
        parent::__constructor();
    }

    protected function getPointFontHeader(): int
    {
        return 10;
    }

    protected function getColumnInPrecent(): array
    {
        return [30, 20, 15, 20, 20];
    }

    protected function getPointFontText(): int
    {
        return 10;
    }

    public function render()
    {
        return $this->Output($this->getNameFile());
    }
}
