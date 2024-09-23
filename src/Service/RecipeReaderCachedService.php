<?php

namespace App\Service;

use App\Dto\RecipeDetailsDto;
use App\Interface\RecipeReaderInterface;
use Psr\Cache\CacheItemPoolInterface;

class RecipeReaderCachedService implements RecipeReaderInterface
{
    private const EXPIRE_AFTER = 5*60;

    public function __construct(
        private readonly RecipeReaderService $recipeReader,
        private readonly CacheItemPoolInterface $cache
    ) {
    }

    public function getRecipeDetails(int $recipeId): ?RecipeDetailsDto
    {
        $item = $this->cache->getItem(self::getRecipeDetailsKeyName($recipeId));
        if ($item->isHit()) {
            return $item->get();
        }

        $dto = $this->recipeReader->getRecipeDetails($recipeId);
        if ($dto !== null) {
            $item->set($dto);
            $item->expiresAfter(self::EXPIRE_AFTER);
            $this->cache->save($item);
        }

        return $dto;
    }

    public static function getRecipeDetailsKeyName(int $recipeId): string
    {
        return 'recipe_details_' . $recipeId;
    }
}
