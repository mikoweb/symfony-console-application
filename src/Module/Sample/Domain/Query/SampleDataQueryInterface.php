<?php

namespace App\Module\Sample\Domain\Query;

use App\Module\Core\Domain\Exception\FailedQueryException;
use App\Module\Sample\Domain\DTO\SampleDTO;

interface SampleDataQueryInterface
{
    /**
     * @return SampleDTO[]
     * @throws FailedQueryException
     */
    public function getData(): array;
}
