<?php

namespace Tests\Unit\Integration\Service;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Service\RecipeReaderService;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeReaderServiceTest extends KernelTestCase
{
    public function testGetRecipeDetails(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $recipe = new Recipe();
        $recipe
            ->setTitle('title')
            ->setDescription('description')
            ->setGoodForPersonCount(2)
            ->setCookingDuration(120)
        ;

        $newRecipeService = $this->createMock(RecipeRepository::class);
        $newRecipeService->expects(self::once())
            ->method('find')
            ->willReturn($recipe)
        ;

        $container->set(RecipeRepository::class, $newRecipeService);

        /** @var RecipeReaderService $recipeReaderService */
        $recipeReaderService = $container->get(RecipeReaderService::class);

        $recipeDetails = $recipeReaderService->getRecipeDetails(123);
        $this->assertEquals(
            [
                'id' => null,
                'title' => 'title',
                'description' => 'description',
                'good_for_person_count' => 2,
                'cooking_duration' => 120,
                'total_calories' => 0,
                'ingredients' => [],
            ],
            $recipeDetails->jsonSerialize()
        );
    }
}
