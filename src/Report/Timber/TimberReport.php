<?php

declare(strict_types=1);

namespace App\Report\Timber;

use App\Dataset\PdfDataset;
use App\Report\AbstractReport;
use App\Repository\TimberRepository;
use DatePeriod;

final class TimberReport extends AbstractReport
{
    private TimberRepository $repository;

    public function __construct(DatePeriod $period, TimberRepository $repository)
    {
        $this->period = $period;
        $this->repository = $repository;
        $this->setLabels([
            'Порода',
            'Ø, см',
            'Длина, м',
            'Кол-во, шт',
            'Объём, м³',
        ]);
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
        return "Отчёт по брёвнам";
    }

    protected function updateDataset(): bool
    {

        $timbers = $this->repository->findVolumeTimberByPeriod($this->getPeriod());
        
        if (!$timbers)
            die('В данный период нет брёвен');
        $dataset = new PdfDataset($this->getLabels());

        $buff['diam'] = -1;
        $buff['name_species'] = '';

        foreach ($timbers as $key => $row) {

            $name_species = $row['name_species'];
            $diam = $row['diam'];
            $st_length = $row['st_length'];
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
                $st_length / 1000, //мм в м
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
