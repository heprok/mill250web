<?php

declare(strict_types=1);

namespace App\Report\Timber;

use App\Dataset\PdfDataset;
use App\Entity\Column;
use App\Entity\Shift;
use Tlc\ReportBundle\Report\AbstractReport;
use App\Repository\TimberRepository;
use DatePeriod;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;

final class TimberReport extends AbstractReport
{
    /**
     *
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
        $summaryStatsMaterial['boards'] = new SummaryStatMaterial(
            name: 'Пиломатериалы',
            value: $this->repository->getVolumeBoardsByPeriod($this->period, $this->sqlWhere),
            count: $this->repository->getCountBoardsByPeriod($this->period, $this->sqlWhere),
            suffixValue: 'м³',
            suffixCount: 'шт'
        );
        $summaryStatsMaterial['timber'] = new SummaryStatMaterial(
            name: 'Брёвна',
            value: $this->repository->getVolumeTimberByPeriod($this->period, $this->sqlWhere),
            count: $this->repository->getCountTimberByPeriod($this->period, $this->sqlWhere),
            suffixValue: 'м³',
            suffixCount: 'шт'
        );

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
        return "по брёвнам";
    }

    protected function updateDataset(): bool
    {

        $timbers = $this->repository->getReportVolumeTimberByPeriod($this->getPeriod(), $this->getSqlWhere());

        if (!$timbers)
            die('В данный период нет брёвен');
        $mainDataSetColumns = [
            new Column(title: 'Порода', precentWidth: 30, group: true, align: 'C', total: false),
            new Column(title: 'Ø, см', precentWidth: 20, group: true, align: 'C', total: false),
            new Column(title: 'Длина, м', precentWidth: 20, group: false, align: 'C', total: false),
            new Column(title: 'Кол-во, шт', precentWidth: 15, group: false, align: 'C', total: true),
            new Column(title: 'Объём, м³', precentWidth: 15, group: false, align: 'R', total: true),
        ];
        $mainDataset = new PdfDataset(
            columns: $mainDataSetColumns,
            textTotal: 'Общий итог',
            textSubTotal: 'Итог'
        );
        $buff['diam'] = -1;
        $buff['name_species'] = '';

        foreach ($timbers as $key => $row) {

            $name_species = $row['name_species'];
            $diam = (int)$row['diam'];
            $st_length = number_format($row['st_length'] / 1000, 1);
            $count_timber = $row['count_timber'];
            $volume_boards = (float)$row['volume_boards'];

            if (($buff['diam'] != $diam || $buff['name_species'] != $name_species) && $key != 0) {
                $mainDataset->addSubTotal();
            }

            $buff['name_species'] = $name_species;
            $buff['diam'] = $diam;

            $mainDataset->addRow([
                $name_species,
                $diam,
                $st_length, //мм в м
                $count_timber,
                $volume_boards
            ]);
        }
        $mainDataset->addSubTotal();
        $mainDataset->addTotal();


        $this->addDataset($mainDataset);
        return true;
    }
}
