<?php

declare(strict_types=1);

namespace App\Report;

use App\Dataset\AbstractDataset;
use App\Entity\Shift;
use DatePeriod;
use Exception;
use Transliterator;

abstract class AbstractReport
{
    const DECIMAL_FORMAT = 4;
    const FORMAT_DATE_TIME = 'd.m.Y H:i:s';
    const FORMAT_DATE_FROM_DB = 'Y-m-d H:i:s';
    
    private array $datasets = [];
    protected array $labels = [];
    protected DatePeriod $period;
    protected ?Shift $shift;

    abstract public function getNameReport(): string;
    abstract protected function updateDataset(): bool;
    
    public function __construct(DatePeriod $period, Shift $shift = null)
    {
        $this->period = $period;
        $this->shift = $shift;
    }

    public function addDataset(AbstractDataset $dataset): self
    {
        $this->datasets[] = $dataset;
        return $this;
    }

    public function getPeriod(): DatePeriod
    {
        return $this->period;
    }

    public function init(): bool
    {
        if (empty($this->datasets))
            return $this->updateDataset();

        return true;
    }

    public function getShift(): ?Shift
    {
        return $this->shift;
    }
    /**
     * @return AbstractDataset[] Returns an array of AbstractDataset objects
     */
    public function getDatasets(): array
    {
        return $this->datasets;
    }

    /**
     * @return string[] Return an array string
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    public function setLabels(array $labels): self
    {
        $this->labels = $labels;
        return $this;
    }

    public function getNameReportTranslit()
    {
        $str = transliterator_transliterate('Latin-ASCII', transliterator_transliterate('Latin', $this->getNameReport()));
        $str = str_replace(' ', '_', $str);
        return $str;
    }
}
