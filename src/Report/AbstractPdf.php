<?php

namespace App\Report;

use App\Entity\Shift;
use DateInterval;
use DatePeriod;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use TCPDF;

abstract class AbstractPdf extends TCPDF
{
    protected AbstractReport $report;
    const COLOR_GRAY = 238;
    const DATE_FORMAT = 'd.m.Y H:i:s';
    const TIME_FORMAT_FOR_INTERVAL = '%H:%I:%S';
    const DATE_FORMAT_FOR_DOWNLOAD = 'd-m-Y H:i';
    const REG_EXP_FOR_TOTAL = '/([а-яА-Я\_\№ё\-a-zA-Z\s\d\(\)\.\:\,\×\⨯]+){(\d)}/um';
    const MARGIN_LEFT = 20;
    const MARGIN_TOP = 20;
    const WIDTH_LOGO = 14;
    const WIDTH_LOGO_BIG = 110;
    const HEIGHT_LOGO_BIG = 50;
    const PRECISION_FOR_FLOAT = 3;

    /**
     * Задаёт размеры для столбца, указывать в процентах
     * В сумме должно быть 100
     * @return int[]
     */
    abstract protected function getColumnInPrecent(): array;
    abstract protected function getHeightCell(): int;
    /**
     * Кегль для текста
     * @return integer
     */

    abstract protected function getPointFontText(): int;
    abstract protected function getAlignForColumns(): array;
    /**
     * Кегль для шапки
     * @return integer
     */
    abstract protected function getPointFontHeader(): int;
    // abstract protected function paintTable(array $header, array $data);


    public function __constructor(
        string $orientation = 'P'
    ) {
        parent::__construct($orientation);
        $this->SetCreator('TechoLesCom');
        $this->SetMargins(self::MARGIN_LEFT, self::MARGIN_TOP);
        $this->SetAutoPageBreak(true, 20);

        $datasets = $this->report->getDatasets();
        $this->startPageGroup();
        $this->setPrintFooter(false);
        $this->setPrintHeader(false);
        $this->AddPage();
        $this->SetFont('dejavusans', '', 11);
        $this->SetXY(self::MARGIN_LEFT, self::MARGIN_TOP);
        $this->paintTitlePage();
        $this->endPage();
        $this->startPageGroup();
        $this->setPrintFooter(true);
        $this->setPrintHeader(true);
        $this->AddPage();
        $this->paintTable($this->report->getLabels(), $datasets);
    }


    /**
     * Возращает ширины для столбцов в мм
     * @return array
     */
    protected function getPuntForColumns(): array
    {
        $widthColumnsInPunt = [];

        foreach ($this->getColumnInPrecent() as $widthColumn) {
            $widthColumnsInPunt[] = ($this->getPageWidth() - self::MARGIN_LEFT - self::MARGIN_LEFT) * $widthColumn / 100;
            // dump($widthColumn / 100);
            // dump(($this->getPageWidth() - self::MARGIN_LEFT - self::MARGIN_LEFT) * $widthColumn / 100);
        }
        // dd($widthColumnsInPunt);
        return $widthColumnsInPunt;
    }


    protected function getWidthColumnForSpan(int $rowspan): int
    {
        $puntColumns = $this->getPuntForColumns();
        $result = 0;
        for ($i = 0; $i < $rowspan; $i++) {
            $result += $puntColumns[$i];
        }
        return $result;
    }


    public function render()
    {
        return $this->Output($this->getNameFile());
    }

    public function header()
    {
        $package = new Package(new EmptyVersionStrategy());
        // dd($package->getUrl('build/logo.png'));
        $this->SetFont('dejavusans', '', 14);
        $image_file = $package->getUrl('build/images/logosmall.svg');
        $this->ImageSVG($image_file, self::MARGIN_LEFT, 3, self::WIDTH_LOGO, 15, 'www.techno-les.com', 'L', false, 0, 0);
        $this->setX(self::WIDTH_LOGO + self::MARGIN_LEFT + 15);
        $this->Cell(0, 20, $this->getNameReport(), 0, 0, 'L', 0, '',  0, false, 'М', 'М');
        $this->Ln(10, true);
        $this->SetFont('dejavusans', '', 10);
        $this->SetY(self::MARGIN_TOP / 2 / 2, false);
        $this->Cell($this->getPageWidth() - self::MARGIN_LEFT - self::MARGIN_LEFT / 2, 0, 'с ' . $this->getPeriod()->getStartDate()->format(self::DATE_FORMAT), 0, 0, 'R', 0, '',  0, false, 'М', 'М');
        $this->ln();
        $this->SetY(self::MARGIN_TOP / 2, false);
        $this->Cell($this->getPageWidth() - self::MARGIN_LEFT - self::MARGIN_LEFT / 2, 0, 'до ' . $this->getPeriod()->getEndDate()->format(self::DATE_FORMAT), 0, 0, 'R', 0, '',  0, false, 'М', 'М');
        // $this->SetY(29);
        // $this->SetLineStyle(array('width' => 2, 'color' => ['#fff']));
        // $this->Line(20, 29, $this->getPageWidth() - 20, $this->getPageHeight() - 20 );
    }

    public function footer()
    {
        $this->SetY(-15);
        $this->SetFont('dejavusans', 'I', 8);
        $this->Cell(0, 10, 'Страница ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function setNameReport(string $name_report)
    {
        $this->name_report = $name_report;
    }

    protected function setReport(AbstractReport $report): self
    {
        $this->report = $report;
        return $this;
    }
    public function getNameReport(): string
    {
        return $this->report->getNameReport();
    }

    public function getPeriod(): DatePeriod
    {
        return $this->report->getPeriod();
    }

    protected function getNameFile(): string
    {
        return $this->report->getNameReportTranslit() . '_' . $this->report->getPeriod()->getStartDate()->format(self::DATE_FORMAT_FOR_DOWNLOAD) . '.pdf';
    }

    /**
     * Рисует данные в таблице
     *
     * @param string[] $header
     * @param PdfDataset[] $data
     * @return void
     */
    protected function paintTable(array $header, array $data)
    {
        $count_dataset = count($data);
        $count_labels = count($this->report->getLabels());
        $puntColumns = $this->getPuntForColumns();
        $alignForColmns = $this->getAlignForColumns();
        // Colors, line width and bold font
        $this->SetFillColor(self::COLOR_GRAY);
        $this->SetTextColor(0);
        // $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B', $this->getPointFontHeader());
        // Header
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($puntColumns[$i], $this->getHeightCell(), $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('', '', $this->getPointFontText());
        // Data
        // $fill = 0;
        for ($i = 0; $i < $count_dataset; $i++) {
            $keys_sub_total = $data[$i]->getKeysSubTotal();
            $data = $data[$i]->getData();
            foreach ($data as $key => $row) {
                if (!in_array($key, $keys_sub_total)) {
                    for ($j = 0; $j < $count_labels; $j++) {
                        if ($row[$j] instanceof DateInterval) {
                            $this->Cell($puntColumns[$j], $this->getHeightCell(), $row[$j]->format(self::TIME_FORMAT_FOR_INTERVAL), 1, 0, $alignForColmns[$j], 0);
                        } elseif (is_float($row[$j])) {
                            $this->Cell($puntColumns[$j], $this->getHeightCell(), number_format($row[$j], self::PRECISION_FOR_FLOAT), 1, 0, $alignForColmns[$j], 0);
                        } else {
                            $this->Cell($puntColumns[$j], $this->getHeightCell(), $row[$j], 1, 0, $alignForColmns[$j], 0);
                        }
                    }
                    $this->Ln();
                } else {
                    $reg = self::REG_EXP_FOR_TOTAL;
                    preg_match_all($reg, $row, $matches, PREG_SET_ORDER, 0);
                    $buff['currentColumn'] = 0;
                    foreach ($matches as $key => $match) {
                        $rowspan = $match[2];
                        $text = $match[1];
                        if ($rowspan >= 2) {
                            $widthColumn = $this->getWidthColumnForSpan($rowspan);
                            $this->Cell($widthColumn, $this->getHeightCell(), $text, 1, 0, 'R', 1);
                            // $buff['currentColumn'] += $widthColumn;
                            $buff['currentColumn'] += $rowspan;
                        } else {
                            //Если выводится интвервал ( 1 д. 03:00:00 ), то пропускается, иначе форматирует float до PRECISION_FOR_FLOAT
                            if (!$this->isInterval($text))
                                $text = strripos($text, '.') ? number_format($text, self::PRECISION_FOR_FLOAT) : $text;
                            $this->Cell($puntColumns[$buff['currentColumn'] + $rowspan - 1], $this->getHeightCell(), $text, 1, 0, $alignForColmns[$buff['currentColumn'] + $rowspan - 1], 1);
                            $buff['currentColumn'] += $rowspan;
                        }
                    }

                    $this->Ln();
                }
            }
            // $this->setPage(1);
            // $this->SetY(self::MARGIN_LEFT + 10);
        }
        // $this->Cell(array_sum($puntColumns), 0, '', 'T');
    }
    private function isInterval(string $interval): bool
    {
        $isContainDay = (bool)stripos($interval, 'д.');
        $isContainMounth = (bool)stripos($interval, 'м.');
        $isContainTime = (bool)stripos($interval, ':');
        return $isContainDay || $isContainMounth || $isContainTime;
    }
    protected function paintTitlePage()
    {
        // $this->setFont('', '', 16)

        $widthPage = $this->getPageWidth();
        $heightPage = $this->getPageHeight();
        $nameReport = $this->report->getNameReport();
        $namesOperator = '';
        $peoples = $this->report->getPeoples();
        if (count($peoples) == 1) {
            $namesOperator = 'Оператор: ' . $peoples[0]->getFullFio();
        } else if (count($peoples) > 0) {
            $namesOperator = 'Операторы: ';
            foreach ($peoples as $people) {
                $namesOperator .= $people->getFullFio() . "<br />";
            }
        }
        $period = $this->report->getPeriod();
        $textPeriod = $period->start->format(self::DATE_FORMAT) . ' по ' . $period->end->format(self::DATE_FORMAT);
        $this->SetXY($widthPage / 2 - self::MARGIN_LEFT, $heightPage / 2 - self::MARGIN_TOP);
        $summaryStats = $this->report->getSummaryStats();
        $summaryStatsHtml = '';
        foreach($summaryStats as $summaryStat ){
            $nameTitle = $summaryStat->getName();
            $value = number_format($summaryStat->getValue(), self::PRECISION_FOR_FLOAT);
            $count = $summaryStat->getCount();
            $summaryStatsHtml .= '<tr>';
            $summaryStatsHtml .= "<td style='text-align: left;'>$nameTitle</td>";
            $summaryStatsHtml .= "<td style='text-align: center;'>$value</td>";
            $summaryStatsHtml .= "<td style='text-align: center;'>$count</td>";
            $summaryStatsHtml .= '</tr>';
        }
        $package = new Package(new EmptyVersionStrategy());
        $image_file = $package->getUrl('build/images/logotypeBig.svg');
        $this->ImageSVG($image_file, $widthPage / 2 - self::MARGIN_LEFT * 2 - self::WIDTH_LOGO, $heightPage / 2 - self::HEIGHT_LOGO_BIG - self::MARGIN_TOP, self::WIDTH_LOGO_BIG, 50, 'www.techno-les.com', 'L', false, 0, 0);
        $html = <<<EOD
        <div style="text-align: center;">
        <h1> $nameReport за</h1>
        <h3>$textPeriod</h3>
        <h3> $namesOperator </h3>
        <br>
        <br>
        <table style=" border: 1 solid #000;border-collapse: collapse" border="1" >
            <tbody>
                <tr>
                    <th>Хар-ка</th>
                    <th>Значение</th>
                    <th>Кол-во</th>
                </tr>
                $summaryStatsHtml
            </tbody>
        </table>
    </div>
EOD;

        // Print text using writeHTMLCell()
        $this->writeHTMLCell(0, 0, self::MARGIN_LEFT, '', $html, 0, 0, false, true, 'С');
    }
}
