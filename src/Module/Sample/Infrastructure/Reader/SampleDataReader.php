<?php

namespace App\Module\Sample\Infrastructure\Reader;

use App\Module\Sample\Domain\DTO\SampleDTO;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use UnexpectedValueException;

final class SampleDataReader
{
    public function __construct(
        private readonly string $projectDir
    ) {}

    /**
     * @return SampleDTO[]
     */
    public function readFromJson(string $jsonFilePath): array
    {
        $fullPath = "{$this->projectDir}/{$jsonFilePath}";

        if (!file_exists($fullPath)) {
            $this->throwFileNotFound($jsonFilePath);
        }

        $jsonContent = file_get_contents($fullPath);
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

    private function throwFileNotFound(string $jsonFilePath): void
    {
        throw new FileNotFoundException("File $jsonFilePath not found!");
    }

    private function throwUnexpectedJsonData(): void
    {
        throw new UnexpectedValueException('Sample data is not array!');
    }
}
