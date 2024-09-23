<?php

namespace App\Service;

use App\Entity\Ingredient;
use App\Enum\UnitType;
use App\Exception\AlreadyExistsException;
use App\Repository\IngredientRepository;

class IngredientService
{
    public function __construct(
        private readonly IngredientRepository $ingredientRepository
    ) {
    }

    public function count(): int
    {
        return $this->ingredientRepository->count();
    }

    public function list(?int $limit = null, ?int $offset = null): array
    {
        return $this->ingredientRepository->findBy([], ['id' => 'ASC'], $limit, $offset);
    }

    public function get(int $ingredientId): ?Ingredient
    {
        return $this->ingredientRepository->find($ingredientId);
    }

    /**
     * @throws AlreadyExistsException
     */
    public function add(Ingredient $ingredient): Ingredient
    {
        $storedIngredient = $this->ingredientRepository->findOneByName($ingredient->getName());
        if ($storedIngredient !== null) {
            throw new AlreadyExistsException($ingredient->getName());
        }

        return $this->ingredientRepository->save($ingredient);
    }

    public function update(Ingredient $currentIngredient, Ingredient $newIngredient): Ingredient
    {
        if ($newIngredient->getTitle()) {
            $currentIngredient->setTitle($newIngredient->getTitle());
        }
        if ($currentIngredient->getUnitType() === UnitType::NONE) {
            $currentIngredient->setUnitType($newIngredient->getUnitType());
        }
        if ($newIngredient->getUnitCalories() > 0) {
            $currentIngredient->setUnitCalories($newIngredient->getUnitCalories());
        }
        if ($newIngredient->getIsVegetarian()) {
            $currentIngredient->setIsVegetarian($newIngredient->getIsVegetarian());
        }

        return $this->ingredientRepository->save($currentIngredient);
    }
}
