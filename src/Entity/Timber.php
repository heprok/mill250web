<?php

namespace App\Entity;

use App\Repository\TimberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimberRepository::class)]
#[ORM\Table(schema: "mill", name: "timber", options: ["comment" => "Брёвна"])]
class Timber
{

    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: "bigint")]
    private $id;

    #[ORM\Column(type: "integer", options: ["comment" => "Сканер ID"])]
    private $scid;

    #[ORM\Column(type: "datetime", options: ["comment" => "Время записи"])]
    private $drec;


    #[ORM\Column(type: "float", options: ["comment" => "Диаметр вершины, мм"])]
    private $top;


    #[ORM\Column(type: "float", options: ["comment" => "Диаметр комля, мм"])]
    private $butt;

    #[ORM\Column(type: "float", options: ["comment" => "Сбег вершины, мм/м2"])]
    private $top_taper;

    #[ORM\Column(type: "float", options: ["comment" => "Сбег комля, мм"])]
    private $butt_taper;

    #[ORM\Column(type: "integer", options: ["comment" => "Длина бревна, мм"])]
    private $length;

    #[ORM\Column(type: "float", options: ["comment" => "Кривизна, %"])]
    private $sweep;


    #[ORM\Column(type: "float", options: ["comment" => "Учётный диаметр по ГОСТ, см"])]
    private $diam;

    #[ORM\Column(type: "bnom[]", options: ["comment" => "Номинальные размеры досок"])]
    private $boards;


    #[ORM\ManyToOne(targetEntity: Postav::class)]
    private $postav;

    #[ORM\ManyToOne(targetEntity: Species::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $species;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScid(): ?int
    {
        return $this->scid;
    }

    public function setScid(int $scid): self
    {
        $this->scid = $scid;

        return $this;
    }

    public function getDrec(): ?\DateTimeInterface
    {
        return $this->drec;
    }

    public function setDrec(\DateTimeInterface $drec): self
    {
        $this->drec = $drec;

        return $this;
    }

    public function getTop(): ?float
    {
        return $this->top;
    }

    public function setTop(float $top): self
    {
        $this->top = $top;

        return $this;
    }

    public function getButt(): ?float
    {
        return $this->butt;
    }

    public function setButt(float $butt): self
    {
        $this->butt = $butt;

        return $this;
    }

    public function getTopTaper(): ?float
    {
        return $this->top_taper;
    }

    public function setTopTaper(float $top_taper): self
    {
        $this->top_taper = $top_taper;

        return $this;
    }

    public function getButtTaper(): ?float
    {
        return $this->butt_taper;
    }

    public function setButtTaper(float $butt_taper): self
    {
        $this->butt_taper = $butt_taper;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getSweep(): ?float
    {
        return $this->sweep;
    }

    public function setSweep(float $sweep): self
    {
        $this->sweep = $sweep;

        return $this;
    }

    public function getDiam(): ?float
    {
        return $this->diam;
    }

    public function setDiam(float $diam): self
    {
        $this->diam = $diam;

        return $this;
    }

    public function getBoards()
    {
        return $this->boards;
    }

    public function setBoards($boards): self
    {
        $this->boards = $boards;

        return $this;
    }

    public function getPostav(): ?Postav
    {
        return $this->postav;
    }

    public function setPostav(?Postav $postav): self
    {
        $this->postav = $postav;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(?Species $species): self
    {
        $this->species = $species;

        return $this;
    }
}
