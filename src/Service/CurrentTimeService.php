<?php

namespace App\Service;

use App\Interface\CurrentTimeInterface;

class CurrentTimeService implements CurrentTimeInterface
{

    public function getCurrentTime(): \DateTimeImmutable
    {
       return new \DateTimeImmutable();
    }
}
