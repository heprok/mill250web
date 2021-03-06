<?php

declare(strict_types=1);

namespace App\Report\Board;

use App\Dataset\PdfDataset;
use App\Entity\Column;
use App\Entity\Shift;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;
use App\Report\AbstractReport;
use App\Repository\TimberRepository;
use DatePeriod;

final class BoardFromPostavReport extends AbstractReport
{
    /**
     * @param DatePeriod $period
     * @param TimberRepository $repository
     * @param People[] $people
     */
    public function __construct(
        DatePeriod $period,
        private TimberRepository $repository,
        array $people = [],
        array $sqlWhere = []
    ) {
        parent::__construct($period, $people, $sqlWhere);
    }

    /**
     * @return SummaryStatMaterial[]
     */
    public function getSummaryStatsMaterial(): array
    {
        $summaryStatsMaterial = [];
        $summaryStatsMaterial['boards'] = new SummaryStatMaterial('Пиломатериалы', $this->repository->getVolumeBoardsByPeriod($this->period, $this->sqlWhere), $this->repository->getCountBoardsByPeriod($this->period, $this->sqlWhere), 'м³', 'шт');
        $summaryStatsMaterial['timber'] = new SummaryStatMaterial('Брёвна', $this->repository->getVolumeTimberByPeriod($this->period, $this->sqlWhere), $this->repository->getCountTimberByPeriod($this->period, $this->sqlWhere), 'м³', 'шт');

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

    public function getNameReport(): string
    {
        return "из постава по пиломатериалам";
    }

    protected function updateDataset(): bool
    {
        $timbers = $this->repository->getReportVolumeBoardFromPostavByPeriod($this->getPeriod(), $this->getSqlWhere());
        if (!$timbers)
            die('В данный период нет брёвен');

        $mainDataSetColumns = [
            new Column(title: 'Постав', precentWidth: 30, group: true, align: 'C', total: false),
            new Column(title: 'Ø, см', precentWidth: 10, group: false, align: 'C', total: false),
            new Column(title: 'Порода', precentWidth: 18, group: false, align: 'C', total: false),
            new Column(title: 'Сечение, мм', precentWidth: 11, group: false, align: 'C', total: false),
            new Column(title: 'Длина, м', precentWidth: 10, group: false, align: 'C', total: false),
            new Column(title: 'Кол-во, шт', precentWidth: 10, group: false, align: 'C', total: true),
            new Column(title: 'Объём, м³', precentWidth: 10, group: false, align: 'R', total: true),
        ];
        $mainDataset = new PdfDataset(
            columns: $mainDataSetColumns,
            textTotal: 'Общий итог',
            textSubTotal: 'Итог'
        );

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
                $mainDataset->addSubTotal();
            }
            $buff['name_species'] = $name_species;
            $buff['name_postav'] = $name_postav;
            $buff['diam_postav'] = $diam_postav;

            $mainDataset->addRow([
                $name_postav,
                $diam_postav,
                $name_species,
                str_replace(['(', ')', ','], ['', '', '×'], $cut),
                $st_length, //мм в м
                $count_board,
                $volume_boards
            ]);
        }

        $mainDataset->addSubTotal();
        $mainDataset->addTotal();

        $this->addDataset($mainDataset);

        return true;
    }
}
