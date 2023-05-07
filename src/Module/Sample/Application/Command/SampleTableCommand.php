<?php

namespace App\Module\Sample\Application\Command;

use App\Module\Core\Domain\Exception\FailedQueryException;
use App\Module\Sample\Infrastructure\Query\GetSampleDataFromJsonQuery;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'sample:table',
    description: 'Sample command with Table',
)]
class SampleTableCommand extends Command
{
    public function __construct(
        private readonly GetSampleDataFromJsonQuery $getSampleDataFromJsonQuery
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $sampleData = $this->getSampleDataFromJsonQuery->getData();
        } catch (FailedQueryException $exception) {
            return $this->catchCannotLoadSampleData($io);
        }

        $table = new Table($output);
        $table
            ->setHeaders(['First Name', 'Last Name', 'Age'])
        ;

        foreach ($sampleData as $item) {
            $table->addRow([$item->firstName, $item->lastName, $item->age]);
        }

        $table->render();

        return Command::SUCCESS;
    }

    private function catchCannotLoadSampleData(OutputStyle $io): int
    {
        $io->error('Cannot load sample data!');

        return Command::FAILURE;
    }
}
