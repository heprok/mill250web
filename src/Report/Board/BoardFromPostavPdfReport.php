<?php

declare(strict_types=1);

namespace App\Report\Board;

use App\Report\AbstractPdf;
use App\Report\AbstractReport;

final class BoardFromPostavPdfReport extends AbstractPdf
{
    public function __construct(AbstractReport $report)
    {
        $this->setReport($report);
        parent::__constructor('L');
    }

    protected function getPointFontHeader(): int
    {
        return 6;
    }

    protected function getColumnInPrecent(): array
    {
        return [20, 10, 18, 11, 15, 15, 10];
    }
    
    protected function getAlignForColumns():array
    {
        return ['C', 'C', 'C', 'C', 'C', 'C', 'R'];
    }

    protected function getPointFontText(): int
    {
        return 8;
    }
    
    protected function getHeightCell():int
    {
        return 5;
    }

    public function render()
    {
        return $this->Output($this->getNameFile());
    }
}
