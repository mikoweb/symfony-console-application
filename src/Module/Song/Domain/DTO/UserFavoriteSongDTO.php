<?php

namespace App\Module\Song\Domain\DTO;

final class UserFavoriteSongDTO
{
    public function __construct(
        public readonly string $trackId,
        public readonly string $userId,
        public readonly int $playCount,
        public readonly string $name,
        public readonly string $artist,
        public readonly string $genre,
        public readonly string $tags,
    ) {}
}
