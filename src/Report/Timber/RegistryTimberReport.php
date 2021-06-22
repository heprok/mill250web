<?php

declare(strict_types=1);

namespace App\Report\Timber;

use App\Dataset\PdfDataset;
use Tlc\ReportBundle\Entity\BaseEntity;;
use App\Entity\Column;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;
use App\Entity\Timber;
use Tlc\ReportBundle\Report\AbstractReport;
use App\Repository\TimberRepository;
use DatePeriod;

final class RegistryTimberReport extends AbstractReport
{
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

        $mainDataSetColumns = [
            new Column(title: 'Время записи', precentWidth: 15, group: true, align: 'C', total: false),
            new Column(title: 'Порода', precentWidth: 10, group: true, align: 'C', total: false),
            new Column(title: 'D 1, мм', precentWidth: 5, group: true, align: 'C', total: false),
            new Column(title: 'D 2, мм', precentWidth: 5, group: true, align: 'C', total: false),
            new Column(title: 'D u, см', precentWidth: 5, group: true, align: 'C', total: false),
            new Column(title: 'Сбег, мм/м²', precentWidth: 6, group: true, align: 'C', total: false),
            new Column(title: 'Длина, мм', precentWidth: 6, group: true, align: 'C', total: false),
            new Column(title: 'Ст. длина, м', precentWidth: 7, group: true, align: 'C', total: false),
            new Column(title: 'Кривизна, %', precentWidth: 8, group: true, align: 'C', total: false),
            new Column(title: 'Объём, м³', precentWidth: 6, group: true, align: 'C', total: false),
            new Column(title: 'Доски', precentWidth: 30, group: true, align: 'C', total: false),
        ];
        $mainDataset = new PdfDataset(
            columns: $mainDataSetColumns,
            textTotal: 'Общий итог',
            textSubTotal: 'Итог'
        );

        foreach ($timbers as $key => $row) {
            $timber = $row[0];
            if ($timber instanceof Timber) {

                $drec = $timber->getDrec();
                $namePostav = $timber->getPostav()->getName() ?? $timber->getPostav()->getComm();
                $nameSpecies = $timber->getSpecies()->getName();
                $top = (int)$timber->getTop();
                $butt = (int)$timber->getButt();
                $diam = (int)$timber->getDiam();
                $topTaper = (int)$timber->getTopTaper();
                $buttTaper = (int)$timber->getButtTaper();
                $length = $timber->getLength();
                $taper = (int)round(($top - $butt) / $length * 1000);
                $stLength = number_format($row['standart_length'] / 1000, 1);
                $sweep = number_format($timber->getSweep(), 1); // precent
                $volume = (float)$row['volume_timber'];
                $boards = BaseEntity::bnomToArray($timber->getBoards());
                $strBoards = '';
                
                foreach ($boards['boards'] as $section => $board) {
                    $strBoards .= $section . ' - ' . $board['count'] . ' | ';
                }
                $mainDataset->addRow([
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
                    $volume, // объем
                    $strBoards
                ]);
            }
        }

        $this->addDataset($mainDataset);

        return true;
    }
}
