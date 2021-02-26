<?php

declare(strict_types=1);

namespace App\Report\Timber;

use App\Dataset\PdfDataset;
use App\Entity\Shift;
use App\Report\AbstractReport;
use App\Repository\TimberRepository;
use DatePeriod;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;

final class TimberReport extends AbstractReport
{
    private TimberRepository $repository;

    /**
     *
     * @param DatePeriod $period
     * @param TimberRepository $repository
     * @param People[] $people
     */
    public function __construct(DatePeriod $period, TimberRepository $repository, array $people = [], array $sqlWhere = [])
    {
        $this->repository = $repository;
        $this->setLabels([
            'Порода',
            'Ø, см',
            'Длина, м',
            'Кол-во, шт',
            'Объём, м³',
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
            $this->labels[3],
            $this->labels[4]
        ];
    }

    protected function getTextSubTotal(string $name_species, $diam): string
    {
        return 'Итог (' . $name_species . ','  . $diam . '){' . (string)(count($this->getLabels()) - count($this->getColumnTotal())) . '}%0{1}%1{1}';
    }

    protected function getTextTotal(): string
    {
        return 'Общий итог{' . (string)(count($this->getLabels()) - count($this->getColumnTotal())) . '}%0{1}%1{1}';
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
        $dataset = new PdfDataset($this->getLabels());

        $buff['diam'] = -1;
        $buff['name_species'] = '';

        foreach ($timbers as $key => $row) {

            $name_species = $row['name_species'];
            $diam = (int)$row['diam'];
            $st_length = number_format($row['st_length'] / 1000, 1);
            $count_timber = $row['count_timber'];
            $volume_boards = (float)$row['volume_boards'];

            if (($buff['diam'] != $diam || $buff['name_species'] != $name_species) && $key != 0) {
                $dataset->addSubTotal($this->getColumnTotal(), $this->getTextSubTotal($buff['name_species'], $buff['diam']));
            }

            $buff['name_species'] = $name_species;
            $buff['diam'] = $diam;

            $dataset->addRow([
                $name_species,
                $diam,
                $st_length , //мм в м
                $count_timber,
                $volume_boards
            ]);
        }
        $dataset->addSubTotal($this->getColumnTotal(), $this->getTextSubTotal($buff['name_species'], $buff['diam']));
        $dataset->addTotal($this->getColumnTotal(), $this->getTextTotal());


        $this->addDataset($dataset);
        return true;
    }
}
