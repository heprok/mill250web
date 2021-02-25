<?php

declare(strict_types=1);

namespace App\Report\Timber;

use App\Report\AbstractPdf;
use App\Report\AbstractReport;

final class RegistryTimberPdfReport extends AbstractPdf
{
    public function __construct(AbstractReport $report)
    {
        $this->setReport($report);
        parent::__constructor('L', true);
    }

    protected function getPointFontHeader(): int
    {
        return 6;
    }
    protected function getColumnInPrecent(): array
    {
        return [15, 10, 5, 5, 5, 6, 6, 7, 8, 6, 30];
    }
    
    protected function getAlignForColumns():array
    {
        return ['C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'];
    }

    protected function getPointFontText(): int
    {
        return 8;
    }
    
    protected function getHeightCell():int
    {
        return 5;
    }
}
