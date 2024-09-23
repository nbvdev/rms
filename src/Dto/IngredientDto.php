<?php

namespace App\Dto;

use OpenApi\Attributes\Property;

#[OA\Schema]
class IngredientDto implements \JsonSerializable
{

    private ?int $id;
    private ?string $name;
    private ?string $title;
    private ?string $unitType;
    private ?int $unitCalories;
    private ?bool $isVegetarian;

    public function __construct(?int $id, ?string $name, ?string $title, ?string $unitType, ?int $unitCalories, ?bool $isVegetarian)
    {
        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->unitType = $unitType;
        $this->unitCalories = $unitCalories;
        $this->isVegetarian = $isVegetarian;
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

    #[Property('unit_calories')]
    public function getUnitCalories(): ?int
    {
        return $this->unitCalories;
    }

    #[Property('is_vegetarian')]
    public function getIsVegetarian(): ?bool
    {
        return $this->isVegetarian;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'title' => $this->getTitle(),
            'unit_type' => $this->getUnitType(),
            'unit_calories' => $this->getUnitCalories(),
            'is_vegetarian' => $this->getIsVegetarian(),
        ];
    }
}
