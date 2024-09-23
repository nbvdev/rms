<?php

namespace App\Entity;

use App\Repository\RecipeIngredientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeIngredientRepository::class)]
#[ORM\UniqueConstraint('u_receipt_ingredient', ['recipe_id', 'ingredient_id'])]
class RecipeIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(targetEntity: Ingredient::class )]
    #[ORM\JoinColumn(name: 'ingredient_id', referencedColumnName: 'id', nullable: false)]
    private ?Ingredient $ingredient = null;

    #[ORM\Column]
    private ?int $unitQuantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): static
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getUnitQuantity(): ?int
    {
        return $this->unitQuantity;
    }

    public function setUnitQuantity(int $unitQuantity): static
    {
        $this->unitQuantity = $unitQuantity;

        return $this;
    }

    public function addUnitQuantity(int $unitQuantity): static
    {
        $this->unitQuantity += $unitQuantity;

        return $this;
    }
}
