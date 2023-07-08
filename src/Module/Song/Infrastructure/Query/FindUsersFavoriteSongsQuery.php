<?php

namespace App\Module\Song\Infrastructure\Query;

use App\Module\Core\Domain\Exception\FailedQueryException;
use App\Module\Song\Domain\Dataset\MusicInfoDataset;
use App\Module\Song\Domain\Dataset\UserListeningHistoryDataset;
use App\Module\Song\Domain\Dataset\UsersFavoriteSongsDataset;
use App\Module\Song\Domain\DTO\UserFavoriteSongDTO;
use App\Module\Song\Domain\DTO\UserListeningDTO;
use App\Module\Song\Infrastructure\Reader\MusicInfoReader;
use App\Module\Song\Infrastructure\Reader\UserListeningHistoryReader;
use Ramsey\Collection\Map\TypedMap;
use Throwable;

final class FindUsersFavoriteSongsQuery
{
    public function __construct(
        private readonly MusicInfoReader $musicInfoReader,
        private readonly UserListeningHistoryReader $userListeningHistoryReader
    ) {}

    /**
     * @throws FailedQueryException
     */
    public function find(): UsersFavoriteSongsDataset
    {
        $favoriteSongs = new UsersFavoriteSongsDataset();

        $userListeningHistory = $this->loadUserListeningHistory();
        $musicInfo = $this->loadMusicInfo();

        $listeningMap = new TypedMap('string', TypedMap::class);

        foreach ($userListeningHistory as $listeningDTO) {
            $this->appendListeningToMap($listeningDTO, $listeningMap);
        }

        foreach ($listeningMap as $userId => $userSongsCountMap) {
            /** @var TypedMap $userSongsCountMap */

            $maxTrackId = null;
            $maxPlayCount = 0;

            foreach ($userSongsCountMap as $trackId => $playCount) {
                if ($playCount > $maxPlayCount) {
                    $maxTrackId = $trackId;
                    $maxPlayCount = $playCount;
                }
            }

            $songRow = $musicInfo->get($maxTrackId);
            $favoriteSongs->add($this->createUserFavorite($userId, $maxTrackId, $maxPlayCount, $songRow));
        }

        return $favoriteSongs;
    }

    private function appendListeningToMap(UserListeningDTO $listeningDTO, TypedMap $listeningMap): void
    {
        $userId = $listeningDTO->userId;
        $trackId = $listeningDTO->trackId;

        if ($listeningMap->containsKey($userId)) {
            $userSongsCountMap = $listeningMap->get($userId);
        } else {
            $userSongsCountMap = new TypedMap('string', 'int');
            $listeningMap->put($userId, $userSongsCountMap);
        }

        $userSongsCountMap->put(
            $trackId,
            $userSongsCountMap->containsKey($trackId)
                ? $userSongsCountMap->get($trackId) + $listeningDTO->playCount
                : $listeningDTO->playCount
        );
    }

    private function createUserFavorite(
        string $userId,
        string $favoriteSongId,
        int $favoriteSongCount,
        array $songRow
    ): UserFavoriteSongDTO {
        return new UserFavoriteSongDTO(
            trackId: $favoriteSongId,
            userId: $userId,
            playCount: $favoriteSongCount,
            name: $songRow['name'],
            artist: $songRow['artist'],
            genre: $songRow['genre'],
            tags: $songRow['tags']
        );
    }

    /**
     * @throws FailedQueryException
     */
    private function loadUserListeningHistory(): UserListeningHistoryDataset
    {
        try {
            return $this->userListeningHistoryReader->loadDataset();
        } catch (Throwable $exception) {
            throw new FailedQueryException('Cannot load User Listening History!', 0, $exception);
        }
    }

    /**
     * @throws FailedQueryException
     */
    private function loadMusicInfo(): MusicInfoDataset
    {
        try {
            return $this->musicInfoReader->loadDataset();
        } catch (Throwable $exception) {
            throw new FailedQueryException('Cannot load Music Info Dataset!', 0, $exception);
        }
    }
}
