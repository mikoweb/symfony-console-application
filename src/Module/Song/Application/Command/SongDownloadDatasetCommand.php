<?php

namespace App\Module\Song\Application\Command;

use App\Module\Song\Infrastructure\Dataset\Exceptions\SongDatasetExtractException;
use App\Module\Song\Infrastructure\Dataset\SongDatasetDownloader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOException;

#[AsCommand(
    name: 'song:download-dataset',
    description: 'Download Song Dataset',
)]
class SongDownloadDatasetCommand extends Command
{
    private readonly SymfonyStyle $io;

    public function __construct(
        private readonly SongDatasetDownloader $songDatasetDownloader
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        try {
            $this->songDatasetDownloader->downloadDataset();
            $this->io->success('Song Dataset downloaded!');
        } catch (SongDatasetExtractException $exception) {
            return $this->catchCannotUnzipDataset();
        } catch (IOException $exception) {
            return $this->catchFilesystemError($exception);
        }

        return Command::SUCCESS;
    }

    private function catchCannotUnzipDataset(): int
    {
        $this->io->error('Cannot unzip dataset!');

        return Command::FAILURE;
    }

    private function catchFilesystemError(IOException $exception): int
    {
        $this->io->error('Filesystem error!');
        $this->io->error($exception->getMessage());

        return Command::FAILURE;
    }
}
