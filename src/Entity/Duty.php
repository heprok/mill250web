<?php

namespace App\Entity;

use App\Repository\DutyRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DutyRepository::class)
 * @ORM\Table(name="mill.duty",
 *      options={"comment":"Список должностей"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put", "delete"},
 *      normalizationContext={"groups"={"duty:read"}},
 *      denormalizationContext={"groups"={"duty:write"}}
 * )
 */
class Duty
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=2,
     *      options={"fixed":"true"})
     * @Groups({"duty:read", "duty:write"})
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"duty:read", "duty:write"})
     */
    private string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
