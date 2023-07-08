<?php

namespace App\Module\Song\Domain\DTO;

final class UserListeningDTO
{
    public function __construct(
        public readonly string $trackId,
        public readonly string $userId,
        public readonly int $playCount,
    ) {}

    public static function createFromArray(array $data): self
    {
        return new self(
            trackId: $data['track_id'],
            userId: $data['user_id'],
            playCount: $data['playcount']
        );
    }
}
