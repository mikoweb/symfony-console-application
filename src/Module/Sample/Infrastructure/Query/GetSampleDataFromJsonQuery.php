<?php

namespace App\Module\Sample\Infrastructure\Query;

use App\Module\Core\Domain\Exception\FailedQueryException;
use App\Module\Sample\Domain\Query\SampleDataQueryInterface;
use App\Module\Sample\Infrastructure\Reader\SampleDataReader;
use Throwable;

final class GetSampleDataFromJsonQuery implements SampleDataQueryInterface
{
    public function __construct(
        private readonly SampleDataReader $sampleDataReader
    ) {}

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        try {
            return $this->sampleDataReader->readFromJson();
        } catch (Throwable $exception) {
            throw new FailedQueryException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
