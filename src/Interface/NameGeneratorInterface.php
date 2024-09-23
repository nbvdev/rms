<?php

namespace App\Interface;

interface NameGeneratorInterface
{
    public function generate(string $title): string;
}
