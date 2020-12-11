<?php

declare(strict_types=1);

namespace App\Report\Board;

use App\Dataset\PdfDataset;
use App\Report\AbstractReport;
use App\Repository\TimberRepository;
use DatePeriod;

final class BoardFromPostavReport extends AbstractReport
{
    private TimberRepository $repository;

    public function __construct(DatePeriod $period, TimberRepository $repository)
    {
        $this->period = $period;
        $this->repository = $repository;
        $this->setLabels([
            'Постав',
            'Ø, см',
            'Порода',
            'Сечение, мм',
            'Длина, м',
            'Кол-во, шт',
            'Объём, м³'
        ]);
    }

    protected function getColumnTotal(): array
    {
        return [
            $this->labels[5],
            $this->labels[6]
        ];
    }

    protected function getTextSubTotal(string $name_postav, $diam): string
    {
        return 'Итог (' . $name_postav . ','  . $diam . '){' . (string)(count($this->getLabels()) - count($this->getColumnTotal())) . '}%0{1}%1{1}';
    }

    protected function getTextTotal(): string
    {
        return 'Общий итог{' . (string)(count($this->getLabels()) - count($this->getColumnTotal())) . '}%0{1}%1{1}';
    }

    public function getNameReport(): string
    {
        return "Отчёт из постава по доскам";
    }

    protected function updateDataset(): bool
    {
        $timbers = $this->repository->findVolumeBoardFromPostavByPeriod($this->getPeriod());
        if (!$timbers)
            die('В данный период нет брёвен');
        $dataset = new PdfDataset($this->getLabels());

        $buff['diam_postav'] = -1;
        $buff['name_species'] = '';
        $buff['name_postav'] = '';
        foreach ($timbers as $key => $row) {
            $name_postav = $row['name_postav'] ?? 'Без имени';
            $diam_postav = $row['diam_postav'];
            $name_species = $row['name_species']; 
            $st_length = $row['st_length'];
            $cut = $row['cut'];
            $count_board = $row['count_board'];
            $volume_boards = (float)$row['volume_boards'];

            if (( $buff['diam_postav']  != $diam_postav || $buff['name_postav'] != $name_postav || $buff['name_species'] != $name_species) && $key != 0) {
                $dataset->addSubTotal($this->getColumnTotal(), $this->getTextSubTotal($buff['name_postav'], $buff['diam_postav']));
            }
            $buff['name_species'] = $name_species;
            $buff['name_postav'] = $name_postav;
            $buff['diam_postav'] = $diam_postav;
            
            $dataset->addRow([
                $name_postav,
                $diam_postav,
                $name_species, //мм в м
                str_replace(['(', ')', ','], ['', '', '×'], $cut),
                $st_length / 1000, //мм в м
                $count_board,
                $volume_boards
            ]);
        }

        $dataset->addSubTotal($this->getColumnTotal(), $this->getTextSubTotal($buff['name_postav'], $buff['diam_postav']));
        $dataset->addTotal($this->getColumnTotal(), $this->getTextTotal());

        $this->addDataset($dataset);

        return true;
    }
}
