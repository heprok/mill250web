<?php

namespace App\Entity;

class Bnom
{
  private int $thickness;
  private int $width;

  public function __construct(int $thickness, int $width)
  {
    $this->thickness = $thickness;
    $this->width = $width;
  }

  public function __toString()
  {
    return "($this->thickness,$this->width)";
  }

  public function getCut(): string
  {
    return $this->width . '⨯' . $this->thickness;
  }

  public function getWidth(): int
  {
    return $this->width;
  }

  public function setWidth(int $width): self
  {
    $this->width = $width;

    return $this;
  }

  public function getThickness(): int
  {
    return $this->thickness;
  }

  public function setThickness(int $thickness): self
  {
    $this->thickness = $thickness;

    return $this;
  }
}
