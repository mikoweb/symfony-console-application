<?php

namespace App\Module\Sample\Domain\DTO;

final class SampleDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly int $age
    ) {}
}
