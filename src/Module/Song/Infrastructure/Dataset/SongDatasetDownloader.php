<?php

namespace App\Module\Song\Infrastructure\Dataset;

use App\Module\Core\Application\Path\AppPathResolver;
use App\Module\Song\Infrastructure\Dataset\Exceptions\SongDatasetExtractException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use ZipArchive;
use ValueError;

final class SongDatasetDownloader
{
    public function __construct(
        private readonly AppPathResolver $pathResolver,
    ) {}

    /**
     * @throws IOException
     * @throws SongDatasetExtractException
     */
    public function downloadDataset(): void
    {
        $folderName = SongDatasetLocationConstant::FOLDER_NAME;
        $datasetDir = $this->pathResolver->getAppDatasetPath($folderName);
        $dataSetZipFile = $this->pathResolver->getAppDatasetPath("$folderName/" . SongDatasetLocationConstant::FILE_NAME);

        $fs = new Filesystem();
        $fs->mkdir($datasetDir);

        $dataContent = file_get_contents(SongDatasetLocationConstant::DOWNLOAD_URL);
        file_put_contents($dataSetZipFile, $dataContent);

        $zip = new ZipArchive();
        $zip->open($dataSetZipFile);

        try {
            $zip->extractTo($datasetDir);
        } catch (ValueError $error) {
            $fs->remove($dataSetZipFile);
            throw new SongDatasetExtractException($error->getMessage());
        }

        $zip->close();
        $fs->remove($dataSetZipFile);
    }
}
