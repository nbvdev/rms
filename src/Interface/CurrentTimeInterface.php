<?php

namespace App\Interface;

interface CurrentTimeInterface
{
    public function getCurrentTime(): \DateTimeImmutable;
}
