<?php

namespace App\Service;

use App\Dto\IngredientDto;
use App\Dto\RecipeDetailsDto;
use App\Dto\RecipeDto;
use App\Dto\RecipeIngredientDetailsDto;
use App\Dto\RecipeIngredientDto;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;

class DtoCreatorService
{

    public function buildIngredientDto(Ingredient $ingredient): IngredientDto
    {
        return new IngredientDto(
            $ingredient->getId(),
            $ingredient->getName(),
            $ingredient->getTitle(),
            $ingredient->getUnitType()->value,
            $ingredient->getUnitCalories(),
            $ingredient->getIsVegetarian(),
        );
    }

    public function buildRecipeIngredientDto(RecipeIngredient $recipeIngredient): RecipeIngredientDto
    {
        $ingredient = $recipeIngredient->getIngredient();

        return new RecipeIngredientDto(
            $recipeIngredient->getId(),
            $ingredient?->getName(),
            $ingredient?->getTitle(),
            $ingredient?->getUnitType()->value,
            $recipeIngredient->getUnitQuantity(),
        );
    }

    public function buildRecipeDetailsDto(Recipe $recipe): RecipeDetailsDto
    {
        $ingredients = [];
        foreach ($recipe->getIngredients() as $recipeIngredient) {
            $ingredient = $recipeIngredient->getIngredient();
            $ingredients[] = new RecipeIngredientDetailsDto(
                $ingredient?->getId(),
                $ingredient?->getTitle(),
                $ingredient?->getUnitType()->value,
                $recipeIngredient->getUnitQuantity(),
                $ingredient?->getUnitCalories(),
            );
        }

        return new RecipeDetailsDto(
            $recipe->getId(),
            $recipe->getTitle(),
            $recipe->getDescription(),
            $recipe->getGoodForPersonCount(),
            $recipe->getCookingDuration(),
            ... $ingredients
        );
    }

    public function buildRecipeDto(Recipe $recipe): RecipeDto
    {
        return new RecipeDto(
            $recipe->getId(),
            $recipe->getName(),
            $recipe->getTitle(),
            $recipe->getDescription(),
            $recipe->getGoodForPersonCount(),
            $recipe->getCookingDuration(),
        );
    }
}
