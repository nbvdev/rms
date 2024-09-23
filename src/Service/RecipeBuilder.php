<?php

namespace App\Service;

use App\Entity\Recipe;

class RecipeBuilder
{
    private string $recipeName;
    private string $recipeTitle;
    private string $recipeDescription;
    private int $recipeGoodFroPersonCount;
    private int $recipeCookingDuration;

    public function reset(): static
    {
        $this->recipeName = '';
        $this->recipeTitle = '';
        $this->recipeDescription = '';
        $this->recipeGoodFroPersonCount = 0;
        $this->recipeCookingDuration = 0;

        return $this;
    }

    public function setName(?string $name): static
    {
        if ($name !== null) {
            $this->recipeName = $name;
        }

        return $this;
    }

    public function setTitle(?string $title): static
    {
        if ($title !== null) {
            $this->recipeTitle = $title;
        }

        return $this;
    }

    public function setDescription(?string $description): static
    {
        if ($description !== null) {
            $this->recipeDescription = $description;
        }

        return $this;
    }

    public function setGoodForPersonCount(?int $count): static
    {
        if ($count !== null) {
            $this->recipeGoodFroPersonCount = $count;
        }

        return $this;
    }

    public function setCookingDuration(?int $duration): static
    {
        if ($duration !== null) {
            $this->recipeCookingDuration = $duration;
        }

        return $this;
    }

    public function build(): Recipe
    {
        $recipe = new Recipe();

        $recipe->setName($this->recipeName);
        $recipe->setTitle($this->recipeTitle);
        $recipe->setDescription($this->recipeDescription);
        $recipe->setGoodForPersonCount($this->recipeGoodFroPersonCount);
        $recipe->setCookingDuration($this->recipeCookingDuration);

        return $recipe;
    }
}
