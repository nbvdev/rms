<?php

namespace App\Service;

use App\Interface\NameGeneratorInterface;

class NameGeneratorService implements NameGeneratorInterface
{
    public function generate(string $title): string
    {
        return preg_replace('/\s+/', '-', strtolower($title));
    }
}
