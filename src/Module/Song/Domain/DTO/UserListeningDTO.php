<?php

namespace App\Module\Song\Domain\DTO;

final class UserListeningDTO
{
    public function __construct(
        public readonly string $trackId,
        public readonly string $userId,
        public readonly int $playCount,
    ) {}
}
