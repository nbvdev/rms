<?php

namespace App\Controller;

use App\Dto\IngredientDto;
use App\Entity\Ingredient;
use App\Enum\ApiError;
use App\Exception\ApiException;
use App\Exception\InvalidInputDataException;
use App\Interface\NameGeneratorInterface;
use App\Service\DtoCreatorService;
use App\Service\IngredientBuilder;
use App\Service\IngredientService;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/v1/ingredients')]
class ApiIngredientController extends AbstractFOSRestController
{

    public function __construct(
        private readonly IngredientService $ingredientService,
        private readonly IngredientBuilder $ingredientBuilder,
        private readonly NameGeneratorInterface $nameGenerator,
        private readonly DtoCreatorService $dtoCreator,
    ) {
    }

    #[OA\Response(
        response: 200,
        description: 'List ingredients',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model( type: IngredientDto::class)
            )
        ),
    )]
    #[Rest\Get('', name: 'api_ingredients_list')]
    #[Rest\QueryParam(name: 'page', requirements: "\d+", default:"1")]
    #[Rest\QueryParam(name: 'on_page', requirements: "\d+", default:"30")]
    #[Rest\View]
    public function list(ParamFetcherInterface $paramFetcher)
    {
        $page = (int)$paramFetcher->get('page');
        $onPage = (int)$paramFetcher->get('on_page');
        $offset = (max(1, $page) - 1) * $onPage;

        $ingredients = [];
        foreach ($this->ingredientService->list($onPage, $offset) as $ingredient) {
            $ingredients[] = $this->dtoCreator->buildIngredientDto($ingredient);
        }

        return new JsonResponse(
            [
                'count' => $this->ingredientService->count(),
                'page' => $page,
                'on_page' => $onPage,
                'ingredients' => $ingredients,
            ]
        );
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: new Model( type: IngredientDto::class),
            type: 'object',
            example: [
                'name' => null,
                'title' => 'Water',
                'unit_type' => 'millilitre',
                'unit_calories' => 1,
                'is_vegetarian' => true
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Created ingredient',
        content: new OA\JsonContent(
            ref: new Model( type: IngredientDto::class)
        ),
    )]
    #[Rest\Post('', name: 'api_post_ingredient')]
    public function post(Request $request): JsonResponse
    {
        try {
            $ingredient = $this->buildIngredient($request);
            $ingredient = $this->ingredientService->add($ingredient);

            return new JsonResponse($this->dtoCreator->buildIngredientDto($ingredient));
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
        description: 'Get ingredient',
        content: new OA\JsonContent(
            ref: new Model( type: IngredientDto::class)
        ),
    )]
    #[Rest\Get('/{ingredientId}', name: 'api_get_ingredient')]
    public function get(int $ingredientId): JsonResponse
    {
        $ingredient = $this->ingredientService->get($ingredientId);
        if ($ingredient === null) {
            return new JsonResponse(['error' => 'Ingredient not found'], ApiError::NOT_FOUND->value);
        }

        return new JsonResponse($this->dtoCreator->buildIngredientDto($ingredient));
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: new Model( type: IngredientDto::class),
            type: 'object',
            example: [
                'title' => 'Water',
                'unit_calories' => 100,
                'is_vegetarian' => false
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Update ingredient',
        content: new OA\JsonContent(
            ref: new Model( type: IngredientDto::class)
        ),
    )]
    #[Rest\Patch('/{ingredientId}', name: 'api_patch_ingredient')]
    public function patch(int $ingredientId, Request $request): JsonResponse
    {
        $ingredient = $this->ingredientService->get($ingredientId);
        if ($ingredient === null) {
            return new JsonResponse(['error' => 'Ingredient not found'], ApiError::NOT_FOUND->value);
        }

        try {
            $ingredient = $this->ingredientService->update($ingredient, $this->buildIngredient($request));

            return new JsonResponse($this->dtoCreator->buildIngredientDto($ingredient));
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

    /**
     * @throws ApiException
     */
    private function buildIngredient(Request $request): Ingredient
    {
        try {
            $values = $this->getArrayFromRequest($request);

            return $this->buildIngredientFromArray($values);
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

    private function buildIngredientFromArray(array $values): Ingredient
    {
        $title = $values['title'] ?? null;
        $name = $values['name'] ?? ($title ? $this->nameGenerator->generate($title) : null);

        return $this->ingredientBuilder
            ->reset()
            ->setName($name)
            ->setTitle($title)
            ->setUnitType($values['unit_type'] ?? null)
            ->setUnitCalories($values['unit_calories'] ?? null)
            ->setIsVegetarian($values['is_vegetarian'] ?? null)
            ->build();
    }
}
