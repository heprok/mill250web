<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DowntimePlaceRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DowntimePlaceRepository::class)
 * @ORM\Table(name="mill.downtime_place",
 *      options={"comment":"Места простоя"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put"},
 *      normalizationContext={"groups"={"downtime_place:read"}},
 *      denormalizationContext={"groups"={"downtime_place:write"}, "disable_type_enforcement"=true}
 * )
 */
class DowntimePlace
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="id")
     * @Groups({"downtime_place:read", "downtime_place:write"})
     */
    private int $code;

    /**
     * @ORM\Column(type="string", length=128, name="text",
     *      options={"comment":"Название места"})
     * @Groups({"downtime_place:read", "downtime_place:write", "downtime:read"})
     */
    private string $name;

    public function __construct(int $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;
        
        return $this;
    }
    
    public function __toString()
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}