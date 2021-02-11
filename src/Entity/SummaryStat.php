<?php

declare(strict_types=1);

namespace App\Entity;

class SummaryStat
{
    private string $name;
    private float $value;
    private ?int $count;

    public function __construct(string $name, float $value, ?int $count = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->count = $count;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue() : float
    {
        return $this->value;
    }
    
    public function getCount() : ?int
    {
        return $this->count;
    }
}
