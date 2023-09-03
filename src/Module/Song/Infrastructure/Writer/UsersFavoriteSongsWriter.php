<?php

namespace App\Module\Song\Infrastructure\Writer;

use App\Module\Core\Application\Path\AppPathResolver;
use App\Module\Song\Domain\Dataset\UsersFavoriteSongsDataset;
use App\Module\Song\Domain\DTO\UserFavoriteSongDTO;
use League\Csv\Exception;
use League\Csv\Writer;
use Symfony\Component\Filesystem\Filesystem;

final class UsersFavoriteSongsWriter
{
    public function __construct(
        private readonly AppPathResolver $pathResolver
    ) {}

    /**
     * @throws Exception
     */
    public function writeDatasetToCsv(UsersFavoriteSongsDataset $dataset): string
    {
        $targetPath = $this->createTargetPath();
        $writer = Writer::createFromPath($targetPath, 'w+');
        $writer->insertOne(['track_id', 'user_id', 'play_count', 'name', 'artist', 'genre', 'tags']);

        foreach ($dataset as $favoriteSong) {
            /** @var UserFavoriteSongDTO $favoriteSong */
            $writer->insertOne([
                $favoriteSong->trackId,
                $favoriteSong->userId,
                $favoriteSong->playCount,
                $favoriteSong->name,
                $favoriteSong->artist,
                $favoriteSong->genre,
                $favoriteSong->tags
            ]);
        }

        return $targetPath;
    }

    private function createTargetPath(): string
    {
        $reportDir = $this->pathResolver->getStoragePath('users_favorite_songs_report');

        $fs = new Filesystem();
        $fs->mkdir($reportDir);

        $datePrefix = date('Y-m-d-H-i-s');

        return "$reportDir/$datePrefix-users_favorite_songs.csv";
    }
}
