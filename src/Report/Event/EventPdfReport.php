<?php

declare(strict_types=1);

namespace App\Report\Event;

use App\Report\AbstractPdf;
use App\Report\AbstractReport;

final class EventPdfReport extends AbstractPdf
{

    public function __construct(AbstractReport $eventReport)
    {
        $this->setReport($eventReport);
        parent::__constructor();
    }

    protected function getPointFontHeader(): int
    {
        return 10;
    }

    protected function getPointFontText(): int
    {
        return 10;
    }

    protected function getAlignForColumns():array
    {
        return ['L', 'C', 'C', 'L', 'C', 'C', 'R'];
    }

    protected function getHeightCell():int
    {
        return 10;
    }

    protected function getColumnInPrecent(): array
    {
        return [50, 15, 15, 25];
    }

    public function render()
    {
        return $this->Output($this->getNameFile());
    }

    // /**
    //  * Рисует данные в таблице
    //  *
    //  * @param string[] $header
    //  * @param PdfDataset[] $data
    //  * @return void
    //  */
    // public function paintTable(array $header, array $data)
    // {
    //     $count_dataset = count($data);
    //     $count_labels = count($this->report->getLabels());
    //     $puntColumns = $this->getPuntForColumns();
    //     // Colors, line width and bold font
    //     $this->SetFillColor(self::COLOR_GRAY);
    //     $this->SetTextColor(0);
    //     // $this->SetDrawColor(128, 0, 0);
    //     $this->SetLineWidth(0.3);
    //     $this->SetFont('', 'B', self::POINT_FONT_HEADER);
    //     // Header
    //     $num_headers = count($header);
    //     for ($i = 0; $i < $num_headers; ++$i) {
    //         $this->Cell($puntColumns[$i], self::HEIGH_CELL, $header[$i], 1, 0, 'C', 1);
    //     }
    //     $this->Ln();
    //     // Color and font restoration
    //     $this->SetFillColor(224, 235, 255);
    //     $this->SetTextColor(0);
    //     $this->SetFont('', '', self::POINT_FONT_TEXT);
    //     // Data
    //     for ($i = 0; $i < $count_dataset; $i++) {
    //         $keys_sub_total = $data[$i]->getKeysSubTotal();
    //         $data = $data[$i]->getData();
    //         foreach ($data as $key => $row) {
    //             if (!in_array($key, $keys_sub_total)) {
    //                 for ($j = 0; $j < $count_labels; $j++) {
    //                     $this->Cell($puntColumns[$j], self::HEIGH_CELL, $row[$j], 1, 0, 'C', 0);
    //                 }
    //                 $this->Ln();
    //             }
    //         }
    //         $this->setPage(1);
    //         $this->SetY(self::MARGIN_LEFT + 10);
    //     }
    //     $this->Cell(array_sum($puntColumns), 0, '', 'T');
    // }
}
