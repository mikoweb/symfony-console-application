<?php

namespace App\Module\Sample\Infrastructure\Reader;

use App\Module\Sample\Domain\DTO\SampleDTO;
use UnexpectedValueException;

final class SampleDataReader
{
    public function __construct(
        private readonly string $projectDir
    ) {}

    /**
     * @return SampleDTO[]
     */
    public function readFromJson(): array
    {
        $jsonContent = file_get_contents("{$this->projectDir}/storage/json/sample/sample_data_for_table.json");
        $jsonData = json_decode($jsonContent, true);

        if (!is_array($jsonData)) {
            $this->throwUnexpectedJsonData();
        }

        $sampleData = [];
        foreach ($jsonData as $assocItem) {
            $sampleData[] = $this->mapItemToDTO($assocItem);
        }

        return $sampleData;
    }

    private function mapItemToDTO(array $assocItem): SampleDTO
    {
        return new SampleDTO(
            firstName: $assocItem['firstName'],
            lastName: $assocItem['lastName'],
            age: $assocItem['age'],
        );
    }

    private function throwUnexpectedJsonData(): void
    {
        throw new UnexpectedValueException('Sample data is not array');
    }
}
