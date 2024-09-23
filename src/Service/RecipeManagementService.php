<?php

namespace App\Service;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Interface\RecipeReaderSyncInterface;
use App\Repository\RecipeIngredientRepository;

class RecipeManagementService
{

    public function __construct(
        private readonly RecipeIngredientRepository $recipeIngredientRepository,
        private readonly RecipeReaderSyncInterface $recipeReaderSync,
    ) {
    }

    public function add(Recipe $recipe, Ingredient $ingredient, int $quantity): RecipeIngredient
    {
        $recipeIngredient = $this->recipeIngredientRepository->findOneBy([
            'recipe' => $recipe->getId(),
            'ingredient' => $ingredient->getId(),
        ]);

        if ($recipeIngredient === null) {
            $recipeIngredient = new RecipeIngredient();
            $recipeIngredient->setRecipe($recipe);
            $recipeIngredient->setIngredient($ingredient);
        }

        $recipeIngredient->addUnitQuantity($quantity);

        $recipeIngredient = $this->recipeIngredientRepository->save($recipeIngredient);

        $this->recipeReaderSync->onRecipeUpdated($recipe->getId());

        return $recipeIngredient;
    }

    public function delete(Recipe $recipe, Ingredient $ingredient): bool
    {
        $recipeIngredient = $this->recipeIngredientRepository->findOneBy([
            'recipe' => $recipe->getId(),
            'ingredient' => $ingredient->getId(),
        ]);

        if ($recipeIngredient === null) {
            return false;
        }

        $result = $this->recipeIngredientRepository->delete($recipeIngredient);

        $this->recipeReaderSync->onRecipeUpdated($recipe->getId());

        return $result;
    }
}
