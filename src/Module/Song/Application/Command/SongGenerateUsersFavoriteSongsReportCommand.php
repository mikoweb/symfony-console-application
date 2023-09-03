<?php

namespace App\Module\Song\Application\Command;

use App\Module\Core\Domain\Exception\FailedQueryException;
use App\Module\Song\Infrastructure\Query\FindUsersFavoriteSongsQuery;
use App\Module\Song\Infrastructure\Writer\UsersFavoriteSongsWriter;
use League\Csv\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'song:generate-users-favorite-songs-report',
    description: 'Generate user\'s favorite songs report',
)]
class SongGenerateUsersFavoriteSongsReportCommand extends Command
{
    private readonly SymfonyStyle $io;

    public function __construct(
        private readonly FindUsersFavoriteSongsQuery $findUsersFavoriteSongsQuery,
        private readonly UsersFavoriteSongsWriter $usersFavoriteSongsWriter
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        try {
            $dataset = $this->findUsersFavoriteSongsQuery->find();
        } catch (FailedQueryException $exception) {
            return $this->catchFailedQuery();
        }

        try {
            $reportPath = $this->usersFavoriteSongsWriter->writeDatasetToCsv($dataset);

            $this->io->success('Generated user\'s favorite songs report!');
            $this->io->info($reportPath);
        } catch (Exception $exception) {
            return $this->catchFailedWrite($exception);
        }

        return Command::SUCCESS;
    }

    private function catchFailedQuery(): int
    {
        $this->io->error('Failed to download songs data!');

        return Command::FAILURE;
    }

    private function catchFailedWrite(Throwable $exception): int
    {
        $this->io->error('Failed write report!');
        $this->io->error($exception->getMessage());

        return Command::FAILURE;
    }
}
