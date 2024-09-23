<?php

namespace App\Entity;

use App\Enum\UnitType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes\Property;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ORM\UniqueConstraint('u_ingredient_name', ['name'])]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Property(property: 'unit_type', enum: UnitType::class)]
    #[ORM\Column(enumType: UnitType::class)]
    private ?UnitType $unitType = null;

    #[Property('unit_calories')]
    #[ORM\Column]
    private ?int $unitCalories = null;

    #[Property('is_vegetarian')]
    #[ORM\Column]
    private ?bool $isVegetarian = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getUnitType(): ?UnitType
    {
        return $this->unitType;
    }

    public function setUnitType(UnitType $unitType): static
    {
        $this->unitType = $unitType;

        return $this;
    }

    public function getUnitCalories(): ?int
    {
        return $this->unitCalories;
    }

    public function setUnitCalories(int $calories): static
    {
        $this->unitCalories = $calories;

        return $this;
    }

    public function getIsVegetarian(): ?int
    {
        return $this->isVegetarian;
    }

    public function setIsVegetarian(bool $isVegetarian): static
    {
        $this->isVegetarian = $isVegetarian;

        return $this;
    }
}
