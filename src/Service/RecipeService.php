<?php

namespace App\Service;

use App\Entity\Recipe;
use App\Exception\AlreadyExistsException;
use App\Interface\CurrentTimeInterface;
use App\Interface\RecipeReaderSyncInterface;
use App\Repository\RecipeRepository;

class RecipeService
{
    public function __construct(
        private readonly RecipeRepository $recipeRepository,
        private readonly CurrentTimeInterface $currentTime,
        private readonly RecipeReaderSyncInterface $recipeReaderSync,
    ) {
    }

    public function count(): int
    {
        return $this->recipeRepository->count();
    }

    public function list(?int $limit = null, ?int $offset = null): array
    {
        return $this->recipeRepository->findBy([], ['id' => 'ASC'], $limit, $offset);
    }

    public function get(int $recipeId): ?Recipe
    {
        return $this->recipeRepository->find($recipeId);
    }

    /**
     * @throws AlreadyExistsException
     */
    public function add(Recipe $recipe): Recipe
    {
        $storedIngredient = $this->recipeRepository->findOneByName($recipe->getName());
        if ($storedIngredient !== null) {
            throw new AlreadyExistsException($recipe->getName());
        }

        $now = $this->currentTime->getCurrentTime();
        $recipe->setCreated($now);
        $recipe->setUpdated($now);

        return $this->recipeRepository->save($recipe);
    }

    public function update(Recipe $currentRecipe, Recipe $newRecipe): Recipe
    {
        if ($newRecipe->getTitle()) {
            $currentRecipe->setTitle($newRecipe->getTitle());
        }
        if ($newRecipe->getDescription()) {
            $currentRecipe->setDescription($newRecipe->getDescription());
        }
        if ($newRecipe->getGoodForPersonCount() > 0) {
            $currentRecipe->setGoodForPersonCount($newRecipe->getGoodForPersonCount());
        }
        if ($newRecipe->getCookingDuration() > 0) {
            $currentRecipe->setCookingDuration($newRecipe->getCookingDuration());
        }

        $recipe = $this->recipeRepository->save($currentRecipe);

        $this->recipeReaderSync->onRecipeUpdated($recipe->getId());

        return $recipe;
    }
}
