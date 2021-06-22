<?php

namespace App\Entity;

use App\Repository\PostavRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: PostavRepository::class)]
#[ORM\Table(schema: "mill", name: "postav", options: ["comment" => "Таблица поставов в формате JSON"])]
#[
    ApiResource(
        collectionOperations: ["get"],
        itemOperations: ["get"],
        normalizationContext: ["groups" => ["postav:read"]],
        denormalizationContext: ["groups" => ["postav:write"]]
    )
]
class Postav
{

    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: "integer")]
    #[Groups(["postav:read"])]
    private int $id;

    #[ORM\Column(type: "datetime", options: ["comment" => "Время создания"])]
    private $drec;

    #[ORM\Column(type: "text", nullable: true, options: ["comment" => "Примечание"])]
    private $comm;

    #[ORM\Column(type: "json")]
    private $postav = [];

    #[ORM\Column(type: "boolean", options: ["default" => "true"])]
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

    #[Groups(["postav:read"])]
    public function getName(): string
    {
        return $this->postav['name'] != '' ? $this->postav['name'] :  $this->comm;
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
