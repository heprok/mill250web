<?php

declare(strict_types=1);

namespace App\Report\Timber;

use App\Dataset\PdfDataset;
use App\Entity\Column;
use App\Entity\Shift;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;
use App\Report\AbstractReport;
use App\Repository\TimberRepository;
use DatePeriod;
use DateTime;

final class TimberFromPostavReport extends AbstractReport
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
        array $sqlWhere
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
            count: $this->repository->getCountBoardsByPeriodSimpleSql($this->period, $this->sqlWhere),
            suffixValue: 'м³',
            suffixCount: 'шт'
        );
        $summaryStatsMaterial['timber'] = new SummaryStatMaterial(
            name: 'Брёвна',
            value: $this->repository->getVolumeTimberByPeriodSimpleSql($this->period, $this->sqlWhere),
            count: $this->repository->getCountTimberByPeriodSimpleSql($this->period, $this->sqlWhere),
            suffixValue: 'м³',
            suffixCount: 'шт',
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
        return "из постава по брёвнам";
    }

    protected function updateDataset(): bool
    {

        $timbers = $this->repository->getReportVolumeTimberFromPostavByPeriod($this->getPeriod(), $this->getSqlWhere());
        if (!$timbers)
            die('В данный период нет брёвен');

        $mainDataSetColumns = [
            new Column(title: 'Постав', precentWidth: 26, group: true, align: 'C', total: false),
            new Column(title: 'Ø постава, см', precentWidth: 7, group: false, align: 'C', total: false),
            new Column(title: 'Порода', precentWidth: 15, group: false, align: 'C', total: false),
            new Column(title: 'Ø бревна, см', precentWidth: 8, group: false, align: 'C', total: false),
            new Column(title: 'Начало', precentWidth: 15, group: false, align: 'C', total: false),
            new Column(title: 'Окончание', precentWidth: 15, group: false, align: 'C', total: false),
            new Column(title: 'Кол-во, шт', precentWidth: 8, group: false, align: 'R', total: true),
            new Column(title: 'Объём, м³', precentWidth: 8, group: false, align: 'R', total: true),
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
            $diam_postav = (int)$row['diam_postav'] / 10;
            $name_species = $row['name_species'];
            $diam_timber = (int)$row['diam_timber'];
            $count_timber = $row['count_timber'];
            $volume_timber = (float)$row['volume_timber'];
            $date_start_postav = DateTime::createFromFormat(self::FORMAT_DATE_FROM_DB, $row['start_date']);
            $date_end_postav = DateTime::createFromFormat(self::FORMAT_DATE_FROM_DB, $row['end_date']);

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
                $diam_timber,
                $date_start_postav->format(self::FORMAT_DATE_TIME),
                $date_end_postav->format(self::FORMAT_DATE_TIME),
                $count_timber,
                $volume_timber
            ]);
        }

        $mainDataset->addSubTotal();
        $mainDataset->addTotal();


        $this->addDataset($mainDataset);

        return true;
    }
}
