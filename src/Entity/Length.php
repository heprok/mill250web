<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LengthRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LengthRepository::class)
 * @ORM\Table(name="mill.length",
 *      options={"comment":"Cтандартные длины"})
 */
#[
ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: ["get", "put", "delete"],
    normalizationContext: ["groups" => ["length:read"]],
    denormalizationContext: ["groups" => ["length:write"], "disable_type_enforcement" => true]
)]
class Length
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    #[Groups(["length:read", "length:write"])]
    private int $standard;

    /**
     * @ORM\Column(type="integer",
     *      options={"comment":"Минимальная граница диапзаона не включая, мм"})
     */
    #[Groups(["length:read", "length:write"])]
    private int $minimum;

    /**
     * @ORM\Column(type="integer",
     *      options={"comment":"Максимальная граница диапзаона не включая, мм"})
     */
    #[Groups(["length:read", "length:write"])]
    private int $maximum;

    public function __construct(int $standard)
    {
        $this->standard = $standard;
    }

    public function getStandard(): ?int
    {
        return $this->standard;
    }

    public function setStandard(int $standard): self
    {
        $this->standard = $standard;

        return $this;
    }

    public function getMinimum(): ?int
    {
        return $this->minimum;
    }

    public function setMinimum(int $minimum): self
    {
        $this->minimum = $minimum;

        return $this;
    }

    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    public function setMaximum(int $maximum): self
    {
        $this->maximum = $maximum;

        return $this;
    }
}
