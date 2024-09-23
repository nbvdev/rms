<?php
namespace App\Controller;

use App\Service\IngredientService;
use App\Service\RecipeManagementService;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{

    public function __construct(
        private readonly RecipeService          $recipeService,
        private readonly IngredientService $ingredientService,
        private readonly RecipeManagementService $recipeManagementService,
    ) {
    }

    #[Route('/', name: 'app_main')]
    public function index() : Response
    {
//        $recipe = $this->recipeService->get(5);
//        $ingredient = $this->ingredientService->get(1);
//
//        $rel = $this->recipeManagementService->add($recipe, $ingredient, 10);
//var_dump($rel->getIngredient());
        return $this->render('main/index.html.twig', []);
    }
}
