<?php

namespace App\Module\Song\Infrastructure\Reader;

use App\Module\Core\Infrastructure\Dataset\Reader\AbstractCsvDatasetReader;
use App\Module\Song\Domain\Dataset\MusicInfoDataset;
use App\Module\Song\Infrastructure\Dataset\SongDatasetLocationConstant;
use League\Csv\Exception;
use League\Csv\UnavailableStream;

final class MusicInfoReader extends AbstractCsvDatasetReader
{
    /**
     * @throws Exception
     * @throws UnavailableStream
     */
    public function loadDataset(): MusicInfoDataset
    {
        $reader = $this->createReader();
        $dataset = new MusicInfoDataset();

        foreach ($reader->getRecords() as $row) {
            $dataset->put($row['track_id'], $row);
        }

        return $dataset;
    }

    protected function getDatasetFolderName(): string
    {
        return SongDatasetLocationConstant::FOLDER_NAME;
    }

    protected function getDatasetFileName(): string
    {
        return SongDatasetLocationConstant::MUSIC_INFO_FILE_NAME;
    }
}
