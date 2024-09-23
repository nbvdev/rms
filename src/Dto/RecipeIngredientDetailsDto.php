<?php

namespace App\Dto;

use OpenApi\Attributes as OA;
use OpenApi\Attributes\Property;

#[OA\Schema]
class RecipeIngredientDetailsDto implements \JsonSerializable
{
    private ?int $id;
    private ?string $title;
    private ?string $unitType;
    private ?int $unitQuantity;

    private ?int $unitCalories;

    public function __construct(
        ?int $id,
        ?string $title,
        ?string $unitType,
        ?int $unitQuantity,
        ?int $unitCalories,
    ) {
        $this->unitCalories = $unitCalories;
        $this->unitQuantity = $unitQuantity;
        $this->unitType = $unitType;
        $this->title = $title;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    #[Property('unit_calories')]
    public function getUnitCalories(): ?int
    {
        return $this->unitCalories;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'unit_type' => $this->unitType,
            'unit_quantity' => $this->unitQuantity,
            'unit_calories' => $this->unitCalories,
        ];
    }
}
