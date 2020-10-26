<?php

namespace App\Entity;

use App\Repository\PostavRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostavRepository::class)
 * @ORM\Table(name="mill.postav",
 *      options={"comment":"Таблица поставов в формате JSON"})
 */
class Postav
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime",
     *      options={"comment":"Время создания"})
     */
    private $drec;

    /**
     * @ORM\Column(type="text", nullable=true,
     *      options={"comment":"Примечание"})
     */
    private $comm;

    /**
     * @ORM\Column(type="json")
     */
    private $postav = [];

    /**
     * @ORM\Column(type="boolean",
     *      options={"default":"true"})
     */
    private $enabled;

    public function __construct()
    {
        $this->timbers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getComm(): ?string
    {
        return $this->comm;
    }

    public function setComm(?string $comm): self
    {
        $this->comm = $comm;

        return $this;
    }

    public function getPostav(): ?array
    {
        return $this->postav;
    }

    public function setPostav(array $postav): self
    {
        $this->postav = $postav;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
