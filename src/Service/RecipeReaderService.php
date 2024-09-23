<?php

namespace App\Service;

use App\Dto\RecipeDetailsDto;
use App\Interface\RecipeReaderInterface;

class RecipeReaderService implements RecipeReaderInterface
{
    public function __construct(
        private readonly RecipeService     $recipeService,
        private readonly DtoCreatorService $dtoCreatorService,
    ) {
    }


    public function getRecipeDetails(int $recipeId): ?RecipeDetailsDto
    {
        $recipe = $this->recipeService->get($recipeId);
        if ($recipe === null) {
            return null;
        }

        return $this->dtoCreatorService->buildRecipeDetailsDto($recipe);
    }
}
