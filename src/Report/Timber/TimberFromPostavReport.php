<?php

declare(strict_types=1);

namespace App\Report\Timber;

use App\Dataset\PdfDataset;
use App\Entity\Shift;
use App\Report\AbstractReport;
use App\Repository\TimberRepository;
use DatePeriod;
use DateTime;

final class TimberFromPostavReport extends AbstractReport
{
    private TimberRepository $repository;

    /**
     *
     * @param DatePeriod $period
     * @param TimberRepository $repository
     * @param People[] $people
     */
    public function __construct(DatePeriod $period, TimberRepository $repository, array $people = [])
    {
        $this->repository = $repository;
        $this->setLabels([
            'Постав',
            'Ø постава, см',
            'Порода',
            'Ø бревна, см',
            'Начало',
            'Окончание',
            'Кол-во, шт',
            'Объём, м³'
        ]);
        parent::__construct($period, $people);
    }

    protected function getColumnTotal(): array
    {
        return [
            $this->labels[6],
            $this->labels[7]
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
        return "Отчёт из постава по брёвнам";
    }

    protected function updateDataset(): bool
    {

        $timbers = $this->repository->getReportVolumeTimberFromPostavByPeriod($this->getPeriod());
        if (!$timbers)
            die('В данный период нет брёвен');
        $dataset = new PdfDataset($this->getLabels());

        $buff['diam_postav'] = -1;
        $buff['name_species'] = '';
        $buff['name_postav'] = '';

        foreach ($timbers as $key => $row) {
            $name_postav = $row['name_postav'] ?? 'Без имени';
            $diam_postav = (int)$row['diam_postav'] / 10;
            $name_species = $row['name_species']; 
            $diam_timber = $row['diam_timber'];
            $count_timber = $row['count_timber'];
            $volume_timber = (float)$row['volume_timber'];
            $date_start_postav = DateTime::createFromFormat(self::FORMAT_DATE_FROM_DB, $row['start_date']);
            $date_end_postav = DateTime::createFromFormat(self::FORMAT_DATE_FROM_DB, $row['end_date']);

            if (( $buff['diam_postav']  != $diam_postav || $buff['name_postav'] != $name_postav || $buff['name_species'] != $name_species) && $key != 0) {
                $dataset->addSubTotal($this->getColumnTotal(), $this->getTextSubTotal($buff['name_postav'], $buff['diam_postav']));
            }
            $buff['name_species'] = $name_species;
            $buff['name_postav'] = $name_postav;
            $buff['diam_postav'] = $diam_postav;

            $dataset->addRow([
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

        $dataset->addSubTotal($this->getColumnTotal(), $this->getTextSubTotal($buff['name_postav'], $buff['diam_postav']));
        $dataset->addTotal($this->getColumnTotal(), $this->getTextTotal());


        $this->addDataset($dataset);

        return true;
    }
}
