<?php

namespace App\Controller;

use App\Dto\RecipeDetailsDto;
use App\Dto\RecipeDto;
use App\Dto\RecipeIngredientDto;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Enum\ApiError;
use App\Exception\ApiException;
use App\Exception\InvalidInputDataException;
use App\Exception\NotFoundException;
use App\Interface\NameGeneratorInterface;
use App\Service\DtoCreatorService;
use App\Service\IngredientService;
use App\Service\RecipeBuilder;
use App\Service\RecipeManagementService;
use App\Service\RecipeReaderCachedService;
use App\Service\RecipeService;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/v1/recipes')]
class ApiRecipeController extends AbstractFOSRestController
{

    public function __construct(
        private readonly NameGeneratorInterface $nameGenerator,
        private readonly RecipeBuilder          $recipeBuilder,
        private readonly RecipeService          $recipeService,
        private readonly IngredientService $ingredientService,
        private readonly RecipeManagementService $recipeManagementService,
        private readonly DtoCreatorService $dtoCreator,
        private readonly RecipeReaderCachedService $readerCachedService,
    ) {
    }


    #[OA\Response(
        response: 200,
        description: 'List recipes',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model( type: RecipeDto::class)
            )
        ),
    )]
    #[Rest\Get('', name: 'api_recipes_list')]
    #[Rest\QueryParam(name: 'page', requirements: "\d+", default:"1")]
    #[Rest\QueryParam(name: 'on_page', requirements: "\d+", default:"30")]
    #[Rest\View]
    public function list(ParamFetcherInterface $paramFetcher)
    {
        try {
            $page = (int)$paramFetcher->get('page');
            $onPage = (int)$paramFetcher->get('on_page');
            $offset = (max(1, $page) - 1) * $onPage;

            $recipes = [];
            foreach ($this->recipeService->list($onPage, $offset) as $ingredient) {
                $recipes[] = $this->dtoCreator->buildRecipeDto($ingredient);
            }

            return new JsonResponse(
                [
                    'count' => $this->recipeService->count(),
                    'page' => $page,
                    'on_page' => $onPage,
                    'recipes' => $recipes,
                ]
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                ApiError::INTERNAL_ERROR->value
            );
        }
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: new Model( type: RecipeDto::class),
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Created recipe',
        content: new OA\JsonContent(
            ref: new Model( type: RecipeDto::class)
        ),
    )]
    #[Rest\Post('', name: 'api_post_recipe')]
    public function post(Request $request): JsonResponse
    {
        try {
            $recipe = $this->buildRecipe($request);
            $recipe->setOwnerUserId($this->getCurrentUserId());
            $recipe = $this->recipeService->add($recipe);

            return new JsonResponse($this->dtoCreator->buildRecipeDto($recipe));
        } catch (ApiException $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                $exception->getCode()
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                ApiError::INTERNAL_ERROR->value
            );
        }
    }

    #[OA\Response(
        response: 200,
        description: 'Get recipe',
        content: new OA\JsonContent(
            ref: new Model( type: RecipeDto::class)
        ),
    )]
    #[Rest\Get('/{recipeId}', name: 'api_get_recipe')]
    public function get(int $recipeId): JsonResponse
    {
        try {
            $recipe = $this->ensureRecipe($recipeId);

            return new JsonResponse($this->dtoCreator->buildRecipeDto($recipe));
        } catch (ApiException $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                $exception->getCode()
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                ApiError::INTERNAL_ERROR->value
            );
        }
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: new Model( type: RecipeDto::class),
            type: 'object',
            example: [
                "title" => "Hoot Black Tee",
                "description" => "Place a packet of a black tee, pour some hot water",
                "good_for_person_count" => 1,
                "cooking_duration" => 7
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Patch recipe',
        content: new OA\JsonContent(
            ref: new Model( type: RecipeDto::class)
        ),
    )]
    #[Rest\Patch('/{recipeId}', name: 'api_patch_recipe')]
    public function patch(int $recipeId, Request $request): JsonResponse
    {
        try {
            $recipe = $this->ensureRecipe($recipeId);
            $recipe = $this->recipeService->update($recipe, $this->buildRecipe($request));

            return new JsonResponse($this->dtoCreator->buildRecipeDto($recipe));
        } catch (ApiException $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                $exception->getCode()
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                ApiError::INTERNAL_ERROR->value
            );
        }
    }

    #[OA\Response(
        response: 200,
        description: 'Get recipe',
        content: new OA\JsonContent(
            ref: new Model( type: RecipeDetailsDto::class)
        ),
    )]
    #[Rest\Get('/{recipeId}/details', name: 'api_get_recipe_details')]
    public function getDetails(int $recipeId): JsonResponse
    {
        try {
//            $recipe = $this->ensureRecipe($recipeId);
//            $recipeDetailsDto = $this->dtoCreator->buildRecipeDetailsDto($recipe);
            $recipeDetailsDto = $this->readerCachedService->getRecipeDetails($recipeId);
            if ($recipeDetailsDto === null) {
                throw new NotFoundException('recipe');
            }

            return new JsonResponse($recipeDetailsDto);
        } catch (ApiException $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                $exception->getCode()
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                ApiError::INTERNAL_ERROR->value
            );
        }
    }

    #[OA\Response(
        response: 200,
        description: 'List recipe ingredients',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model( type: RecipeIngredientDto::class)
            )
        ),
    )]
    #[Rest\Get('/{recipeId}/ingredients', name: 'api_recipe_list_ingredient')]
    public function getIngredients(int $recipeId): JsonResponse
    {
        try {
            $recipe = $this->ensureRecipe($recipeId);

            $ingredients = [];
            foreach ( $recipe->getIngredients() as $recipeIngredient) {
                $ingredients[] = $this->dtoCreator->buildRecipeIngredientDto($recipeIngredient);
            }

            return new JsonResponse($ingredients);
        } catch (ApiException $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                $exception->getCode()
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                ApiError::INTERNAL_ERROR->value
            );
        }
    }

    #[OA\Response(
        response: 200,
        description: 'Add recipe ingredient',
        content: new OA\JsonContent(
            ref: new Model( type: RecipeIngredientDto::class)
        ),
    )]
    #[Rest\Post('/{recipeId}/ingredients/{ingredientId}/quantity/{quantity}', name: 'api_recipe_add_ingredient')]
    public function postIngredient(int $recipeId, int $ingredientId, int $quantity): JsonResponse
    {
        try {
            $recipe = $this->ensureRecipe($recipeId);
            $ingredient = $this->ensureIngredient($ingredientId);

            $recipeIngredient = $this->recipeManagementService->add($recipe, $ingredient, $quantity);

            return new JsonResponse($this->dtoCreator->buildRecipeIngredientDto($recipeIngredient));
        } catch (ApiException $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                $exception->getCode()
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                ApiError::INTERNAL_ERROR->value
            );
        }
    }

    #[OA\Response(
        response: 200,
        description: 'Add recipe ingredient',
        content: new OA\JsonContent(
            ref: new Model( type: RecipeIngredientDto::class)
        ),
    )]
    #[Rest\Delete('/{recipeId}/ingredients/{ingredientId}', name: 'api_recipe_remove_ingredient')]
    public function deleteIngredient(int $recipeId, int $ingredientId): JsonResponse
    {
        try {
            $recipe = $this->ensureRecipe($recipeId);
            $ingredient = $this->ensureIngredient($ingredientId);

            $deleted = $this->recipeManagementService->delete($recipe, $ingredient);

            return new JsonResponse(['success' => $deleted]);
        } catch (ApiException $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                $exception->getCode()
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                ["error" => $exception->getMessage()],
                ApiError::INTERNAL_ERROR->value
            );
        }
    }

    private function getCurrentUserId(): int
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        return (int)$user?->getId();
    }

    /**
     * @throws NotFoundException
     */
    private function ensureRecipe(int $recipeId): Recipe
    {
        $recipe = $this->recipeService->get($recipeId);

        return $recipe ?? throw new NotFoundException('Recipe');
    }

    private function ensureIngredient(int $ingredientId): Ingredient
    {
        $ingredient = $this->ingredientService->get($ingredientId);

        return $ingredient ?? throw new NotFoundException('Ingredient');
    }

    /**
     * @throws ApiException
     */
    private function buildRecipe(Request $request): Recipe
    {
        try {
            $values = $this->getArrayFromRequest($request);

            return $this->buildRecipeFromArray($values);
        } catch (\JsonException $e) {
            throw new InvalidInputDataException($e->getMessage());
        }
    }

    /**
     * @throws \JsonException
     */
    private function getArrayFromRequest(Request $request): array
    {
        $content = $request->getContent();
        if (!$content) {
            return [];
        }

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    private function buildRecipeFromArray(array $values): Recipe
    {
        $title = $values['title'] ?? null;
        $name = $values['name'] ?? ($title ? $this->nameGenerator->generate($title) : null);

        return $this->recipeBuilder
            ->reset()
            ->setName($name)
            ->setTitle($title)
            ->setDescription($values['description'] ?? null)
            ->setGoodForPersonCount($values['good_for_person_count'] ?? 0)
            ->setCookingDuration($values['cooking_duration'] ?? null)
            ->build();
    }
}
