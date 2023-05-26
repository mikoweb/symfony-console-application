<?php

namespace App\Module\Song\Domain\Dataset;

use App\Module\Song\Domain\DTO\UserFavoriteSongDTO;
use Ramsey\Collection\AbstractCollection;

final class UsersFavoriteSongsDataset extends AbstractCollection
{
    public function getType(): string
    {
        return UserFavoriteSongDTO::class;
    }
}
