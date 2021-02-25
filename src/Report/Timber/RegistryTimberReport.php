<?php

declare(strict_types=1);

namespace App\Report\Timber;

use App\Dataset\PdfDataset;
use App\Entity\BaseEntity;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;
use App\Entity\Timber;
use App\Report\AbstractReport;
use App\Repository\TimberRepository;
use DatePeriod;

final class RegistryTimberReport extends AbstractReport
{
    private TimberRepository $repository;

    public function __construct(DatePeriod $period, TimberRepository $repository, array $people = [], array $sqlWhere = [])
    {
        $this->repository = $repository;
        $this->setLabels([
            'Время записи',
            'Порода',
            'Ø вершины, мм',
            'Ø комля, мм',
            'Ø по гост, см',
            'Сбег, мм/м²',
            // 'Сбег комля, мм/м²',
            'Длина бревна, мм',
            'Ст. длина, мм',
            'Кривизна, %',
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

        return $summaryStatsMaterial;
    }

    /**
     *
     * @return SummaryStat[]
     */
    public function getSummaryStats(): array
    {
        $summaryStats = [];
        // $summaryMaterial = $this->getSummaryStatsMaterial();
        // $precent = number_format($summaryMaterial['boards']->getValue() / $summaryMaterial['timber']->getValue() * 100, 0);
        // $summaryStats[] = new SummaryStat('Суммарный процент выхода', $precent, '%');

        return $summaryStats;
    }

    public function getNameReport(): string
    {
        return "хронология брёвен";
    }

    protected function getColumnTotal(): array
    {
        return [];
    }


    protected function updateDataset(): bool
    {
        $timbers = $this->repository->findByPeriod($this->getPeriod(), $this->getSqlWhere());
        if (!$timbers)
            die('В данный период нет брёвен');
        $dataset = new PdfDataset($this->getLabels());

        foreach ($timbers as $key => $row) {
            $timber = $row[0];
            if ($timber instanceof Timber) {

                $drec = $timber->getDrec();
                $namePostav = $timber->getPostav()->getName() ?? $timber->getPostav()->getComm();
                $nameSpecies = $timber->getSpecies()->getName();
                $top = $timber->getTop();
                $butt = $timber->getButt();
                $diam = (int)$timber->getDiam();
                $topTaper = (int)$timber->getTopTaper();
                $buttTaper = (int)$timber->getButtTaper();
                $length = $timber->getLength();
                $taper = ($top - $butt) / $length * 1000;
                $stLength = $row['standart_length'];
                $sweep = number_format($timber->getSweep(), 2); // precent
                $volume = (float)$row['volume_timber'];
                $boards = BaseEntity::bnomToArray($timber->getBoards());
                $strBoards = '';
                foreach ($boards['boards'] as $section => $board) {
                    $strBoards .= $section . ' - ' . $board['count'] . ' | ';
                }
                $dataset->addRow([
                    $drec->format(self::FORMAT_DATE_TIME),
                    $nameSpecies, //Название породы
                    $top, //Диаметр вершины
                    $butt, // Диаметр комля
                    $diam, // Диаметр по гост
                    $taper,
                    // $topTaper, // Сбег вершины
                    // $buttTaper, // Сбег комля
                    $length, // реальная длина 
                    $stLength, // стандартная длина
                    $sweep, // кривизна
                    // $strBoards,
                    $volume, // объем
                ]);
            }
        }

        $this->addDataset($dataset);

        return true;
    }
}
