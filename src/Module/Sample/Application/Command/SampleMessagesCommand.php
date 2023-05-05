<?php

namespace App\Module\Sample\Application\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'sample:messages',
    description: 'Display messages e.g. warning, error, information',
)]
class SampleMessagesCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('Success');
        $io->warning('Warning!');
        $io->error('Error!');
        $io->info('Info');

        return Command::SUCCESS;
    }
}
