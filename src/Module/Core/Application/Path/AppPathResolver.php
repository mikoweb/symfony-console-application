<?php

namespace App\Module\Core\Application\Path;

final class AppPathResolver
{
    public function __construct(
        private readonly string $projectDir
    ) {}

    public function getAppPath(string $path): string
    {
        return "$this->projectDir/$path";
    }

    public function getAppDatasetPath(string $path): string
    {
        return $this->getAppPath("datasets/$path");
    }

    public function getStoragePath(string $path): string
    {
        return $this->getAppPath("storage/$path");
    }
}
