<?php

namespace App\Module\Song\Domain\Dataset;

use Ramsey\Collection\Map\AbstractTypedMap;

final class MusicInfoDataset extends AbstractTypedMap
{
    public function getKeyType(): string
    {
        return 'string';
    }

    public function getValueType(): string
    {
        return 'array';
    }
}
