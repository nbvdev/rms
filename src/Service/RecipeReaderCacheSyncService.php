<?php

namespace App\Service;

use App\Dto\RecipeDetailsDto;
use App\Entity\Recipe;
use App\Interface\RecipeReaderInterface;
use App\Interface\RecipeReaderSyncInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;

class RecipeReaderCacheSyncService implements RecipeReaderSyncInterface
{
    public function __construct(
        private readonly CacheItemPoolInterface $cache
    ) {
    }

    public function onRecipeUpdated(int $recipeId): void
    {
        $item = $this->cache->getItem(RecipeReaderCachedService::getRecipeDetailsKeyName($recipeId));
        if (!$item->isHit()) {
            return;
        }

        $this->cache->delete($item->getKey());
//        $recipe = $this->recipeRepository->get($recipeId)
//        $dto = $this->dtoCreatorService->buildRecipeDetailsDto($recipe);
//        $item->set($dto);
//        $this->cache->save($item);
    }
}
