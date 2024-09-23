<?php

namespace Tests\Unit\Service;

use App\Service\NameGeneratorService;
use PHPUnit\Framework\TestCase;

class NameGeneratorServiceTest extends TestCase
{
    /**
     * @dataProvider dataProviderGenerate
     */
    public function testGenerate(string $name, string $expected): void
    {
        $service = new NameGeneratorService();
        $this->assertEquals($expected, $service->generate($name));
    }

    public function dataProviderGenerate(): array
    {
        return [
            ['', ''],
            ['foo', 'foo'],
        ];
    }
}
