<?php

namespace App\Module\Song\Domain\Dataset;

use App\Module\Song\Domain\DTO\UserListeningDTO;
use Ramsey\Collection\AbstractCollection;

final class UserListeningHistoryDataset extends AbstractCollection
{
    public function getType(): string
    {
        return UserListeningDTO::class;
    }
}
