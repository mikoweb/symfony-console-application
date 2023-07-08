<?php

namespace App\Module\Song\Infrastructure\Reader;

use App\Module\Core\Infrastructure\Dataset\Reader\AbstractCsvDatasetReader;
use App\Module\Song\Domain\Dataset\UserListeningHistoryDataset;
use App\Module\Song\Domain\DTO\UserListeningDTO;
use App\Module\Song\Infrastructure\Dataset\SongDatasetLocationConstant;
use League\Csv\Exception;
use League\Csv\UnavailableStream;

final class UserListeningHistoryReader extends AbstractCsvDatasetReader
{
    /**
     * @throws Exception
     * @throws UnavailableStream
     */
    public function loadDataset(): UserListeningHistoryDataset
    {
        $reader = $this->createReader();
        $dataset = new UserListeningHistoryDataset();

        foreach ($reader->getRecords() as $row) {
            $dataset->add(UserListeningDTO::createFromArray($row));
        }

        return $dataset;
    }

    protected function getDatasetFolderName(): string
    {
        return SongDatasetLocationConstant::FOLDER_NAME;
    }

    protected function getDatasetFileName(): string
    {
        return SongDatasetLocationConstant::USER_LISTENING_HISTORY_FILE_NAME;
    }
}
