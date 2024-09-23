<?php

namespace App\Interface;

use App\Dto\RecipeDetailsDto;

interface RecipeReaderInterface
{
    public function getRecipeDetails(int $recipeId): ?RecipeDetailsDto;
}
