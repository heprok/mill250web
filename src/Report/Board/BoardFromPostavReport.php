<?php

declare(strict_types=1);

namespace App\Report\Board;

use App\Dataset\PdfDataset;
use App\Entity\Shift;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;
use App\Report\AbstractReport;
use App\Repository\TimberRepository;
use DatePeriod;

final class BoardFromPostavReport extends AbstractReport
{
    private TimberRepository $repository;

    /**
     * @param DatePeriod $period
     * @param TimberRepository $repository
     * @param People[] $people
     */
    public function __construct(DatePeriod $period, TimberRepository $repository, array $people = [], array $sqlWhere = [])
    {
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
        parent::__construct($period, $people, $sqlWhere);
    }

    /**
     * @return SummaryStatMaterial[]
     */
    public function getSummaryStatsMaterial(): array
    {
        $summaryStatsMaterial = [];
        $summaryStatsMaterial['boards'] = new SummaryStatMaterial('Пиломатериалы', $this->repository->getVolumeBoardsByPeriod($this->period), $this->repository->getCountBoardsByPeriod($this->period), 'м³', 'шт');
        $summaryStatsMaterial['timber'] = new SummaryStatMaterial('Брёвна', $this->repository->getVolumeTimberByPeriod($this->period), $this->repository->getCountTimberByPeriod($this->period), 'м³', 'шт');

        return $summaryStatsMaterial;
    }

    /**
     *
     * @return SummaryStat[]
     */
    public function getSummaryStats(): array
    {
        $summaryStats = [];
        $summaryMaterial = $this->getSummaryStatsMaterial();
        $precent = number_format($summaryMaterial['boards']->getValue() / $summaryMaterial['timber']->getValue() * 100, 0);
        $summaryStats[] = new SummaryStat('Суммарный процент выхода', $precent, '%');

        return $summaryStats;
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
        return "из постава по пиломатериалам";
    }

    protected function updateDataset(): bool
    {
        $timbers = $this->repository->getReportVolumeBoardFromPostavByPeriod($this->getPeriod(), $this->getSqlWhere());
        if (!$timbers)
            die('В данный период нет брёвен');
        $dataset = new PdfDataset($this->getLabels());
        $buff['diam_postav'] = -1;
        $buff['name_species'] = '';
        $buff['name_postav'] = '';
        foreach ($timbers as $key => $row) {
            $name_postav = $row['name_postav'] ?? 'Без имени';
            $diam_postav = (int)$row['diam_postav'] / 10; //мм->см
            $name_species = $row['name_species'];
            $st_length = number_format($row['st_length'] / 1000, 1);
            $cut = $row['cut'];
            $count_board = $row['count_board'];
            $volume_boards = (float)$row['volume_boards'] * $count_board;

            if (($buff['diam_postav']  != $diam_postav || $buff['name_postav'] != $name_postav || $buff['name_species'] != $name_species) && $key != 0) {
                $dataset->addSubTotal($this->getColumnTotal(), $this->getTextSubTotal($buff['name_postav'], $buff['diam_postav']));
            }
            $buff['name_species'] = $name_species;
            $buff['name_postav'] = $name_postav;
            $buff['diam_postav'] = $diam_postav;

            $dataset->addRow([
                $name_postav,
                $diam_postav,
                $name_species, 
                str_replace(['(', ')', ','], ['', '', '×'], $cut),
                $st_length, //мм в м
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
