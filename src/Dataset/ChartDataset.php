<?php

declare(strict_types=1);

namespace App\Dataset;

final class ChartDataset extends AbstractDataset
{
    private string $backgroundColor;
    private string $borderColor;
    private string $pointBorderColor;
    private string $pointBackgroundColor;
    private int $borderWidth;
    private string $label;
    private bool $fill;

    public function __construct(string $label)
    {
        $this->label = $label;
        $this->backgroundColor = '#007bff';
        $this->pointBackgroundColor = 'white';
        $this->pointBorderColor = 'white';
        $this->borderColor = '#FC2525';
        $this->borderWidth = 1;
        $this->data = [];
        $this->fill = false;
    }

    public function __serialize(): array
    {
        return [
            'label' => $this->getLabel(),
            'fill' => $this->getFill(),
            'borderColor' => $this->getBorderColor(),
            'backgroundColor' => $this->getBackgroundColor(),
            'pointBorderColor' => $this->getPointBorderColor(),
            'pointBackgroundColor' => $this->getPointBackgroundColor(),
            'borderWidth' => $this->getBorderWidth(),
            'data' => $this->getData(),
        ];
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getBorderColor(): string
    {
        return $this->borderColor;
    }

    public function setBorderColor(string $borderColor): self
    {
        $this->borderColor = $borderColor;

        return $this;
    }

    public function getPointBorderColor(): string
    {
        return $this->pointBorderColor;
    }

    public function setPointBorderColor(string $pointBorderColor): self
    {
        $this->pointBorderColor = $pointBorderColor;

        return $this;
    }

    public function getPointBackgroundColor(): string
    {
        return $this->pointBackgroundColor;
    }

    public function setPointBackgroundColor(string $pointBackgroundColor): self
    {
        $this->pointBackgroundColor = $pointBackgroundColor;

        return $this;
    }

    public function getBorderWidth(): int
    {
        return $this->borderWidth;
    }
    
    public function setBorderWidth(int $borderWidth): self
    {
        $this->borderWidth = $borderWidth;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getFill(): bool
    {
        return $this->fill;
    }

    public function setFill(bool $fill): self
    {
        $this->fill = $fill;

        return $this;
    }
}
