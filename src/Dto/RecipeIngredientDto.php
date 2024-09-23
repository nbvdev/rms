<?php

namespace App\Dto;

use OpenApi\Attributes as OA;
use OpenApi\Attributes\Property;

#[OA\Schema]
class RecipeIngredientDto implements \JsonSerializable
{
    private ?int $id;
    private ?string $name;
    private ?string $title;
    private ?string $unitType;
    private ?int $unitQuantity;

    public function __construct(
        ?int $id,
        ?string $name,
        ?string $title,
        ?string $unitType,
        ?int $unitQuantity
    ) {
        $this->unitQuantity = $unitQuantity;
        $this->unitType = $unitType;
        $this->title = $title;
        $this->name = $name;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    #[Property('unit_type')]
    public function getUnitType(): ?string
    {
        return $this->unitType;
    }

    #[Property('unit_quantity')]
    public function getUnitQuantity(): ?int
    {
        return $this->unitQuantity;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'unit_type' => $this->unitType,
            'unit_quantity' => $this->unitQuantity,
        ];
    }
}
