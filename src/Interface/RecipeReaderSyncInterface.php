<?php

namespace App\Interface;

use App\Entity\Recipe;

interface RecipeReaderSyncInterface
{
    public function onRecipeUpdated(int $recipeId): void;
}
