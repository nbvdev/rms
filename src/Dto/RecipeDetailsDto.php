<?php

namespace App\Dto;

use OpenApi\Attributes\Property;

#[OA\Schema]
class RecipeDetailsDto implements \JsonSerializable
{
    private ?int $id;
    private ?string $title;
    private ?string $description;
    private ?int $goodForPersonCount;
    private ?int $cookingDuration;

    private array $ingredientDetails;

    public function __construct(
        ?int $id,
        ?string $title,
        ?string $description,
        ?int $goodForPersonCount,
        ?int $cookingDuration,
        RecipeIngredientDetailsDto ... $ingredientDetails
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->goodForPersonCount = $goodForPersonCount;
        $this->cookingDuration = $cookingDuration;
        $this->ingredientDetails = $ingredientDetails;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Property('good_for_person_count')]
    public function getGoodForPersonCount(): ?int
    {
        return $this->goodForPersonCount;
    }

    #[Property('cooking_duration')]
    public function getCookingDuration(): ?int
    {
        return $this->cookingDuration;
    }

    /**
     * @return RecipeIngredientDetailsDto[]
     */
    #[Property('ingredients')]
    public function getIngredientDetails(): array
    {
        return $this->ingredientDetails;
    }

    #[Property('total_calories')]
    public function getTotalCalories(): int
    {
        $calories = 0;
        foreach ($this->ingredientDetails as $ingredientDetail) {
            $calories += $ingredientDetail->getUnitCalories() * $ingredientDetail->getUnitQuantity();
        }

        return $calories;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'good_for_person_count' => $this->getGoodForPersonCount(),
            'cooking_duration' => $this->getCookingDuration(),
            'total_calories' => $this->getTotalCalories(),
            'ingredients' => $this->getIngredientDetails(),
        ];
    }

}
