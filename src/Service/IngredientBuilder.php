<?php

namespace App\Service;

use App\Entity\Ingredient;
use App\Enum\UnitType;

class IngredientBuilder
{
    private string $ingredientName;
    private string $ingredientTitle;
    private UnitType $ingredientUnitType;
    private int $ingredientUnitCalories;
    private bool $ingredientIsVegetarian;

    public function reset(): static
    {
        $this->ingredientName = '';
        $this->ingredientTitle = '';
        $this->ingredientUnitType = UnitType::NONE;
        $this->ingredientUnitCalories = 0;
        $this->ingredientIsVegetarian = false;

        return $this;
    }

    public function setName(?string $name): static
    {
        if ($name !== null){
            $this->ingredientName = $name;
        }

        return $this;
    }

    public function setTitle(?string $title): static
    {
        if ($title !== null){
            $this->ingredientTitle = $title;
        }

        return $this;
    }

    public function setUnitType(?string $unitType): static
    {
        if ($unitType !== null){
            $this->ingredientUnitType = UnitType::from($unitType);
        }

        return $this;
    }

    public function setUnitCalories(?int $calories): static
    {
        if ($calories !== null){
            $this->ingredientUnitCalories = $calories;
        }

        return $this;
    }

    public function setIsVegetarian(?bool $isVegetarian): static
    {
        if ($isVegetarian !== null){
            $this->ingredientIsVegetarian = $isVegetarian;
        }

        return $this;
    }

    public function build(): Ingredient
    {
        $ingredient = new Ingredient();

        $ingredient->setName($this->ingredientName);
        $ingredient->setTitle($this->ingredientTitle);
        $ingredient->setUnitType($this->ingredientUnitType);
        $ingredient->setUnitCalories($this->ingredientUnitCalories);
        $ingredient->setIsVegetarian($this->ingredientIsVegetarian);

        return $ingredient;
    }
}
