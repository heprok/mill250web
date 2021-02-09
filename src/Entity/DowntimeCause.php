<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DowntimeCauseRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DowntimeCauseRepository::class)
 * @ORM\Table(name="mill.downtime_cause",
 *      options={"comment":"Причины простоя"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put"},
 *      normalizationContext={"groups"={"downtime_cause:read"}},
 *      denormalizationContext={"groups"={"downtime_cause:write"}, "disable_type_enforcement"=true}
 * )
 */
class DowntimeCause
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="id")
     * @Groups({"downtime_cause:read","downtime_cause:write"})
     */
    private int $code;

    /**
     * @ORM\Column(type="string", length=128, name="text",
     *      options={"comment":"Название причины"})
     * @Groups({"downtime_cause:read", "downtime_cause:write", "downtime:read"})
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