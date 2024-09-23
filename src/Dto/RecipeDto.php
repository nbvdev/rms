<?php

namespace App\Dto;

use OpenApi\Attributes as OA;
use OpenApi\Attributes\Property;

#[OA\Schema]
class RecipeDto implements \JsonSerializable
{
    private ?int $id;
    private ?string $name;
    private ?string $title;
    private ?string $description;
    private ?int $goodForPersonCount;
    private ?int $cookingDuration;

    public function __construct(
        ?int $id,
        ?string $name,
        ?string $title,
        ?string $description,
        ?int $goodForPersonCount,
        ?int $cookingDuration
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->description = $description;
        $this->goodForPersonCount = $goodForPersonCount;
        $this->cookingDuration = $cookingDuration;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'good_for_person_count' => $this->getGoodForPersonCount(),
            'cooking_duration' => $this->getCookingDuration(),
        ];
    }
}
