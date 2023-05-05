<?php

namespace App\Module\Sample\Application\Command;

use App\Module\Core\Application\Console\ConsoleArgumentValidator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints as Assert;
use InvalidArgumentException;

#[AsCommand(
    name: 'sample:progress-bar',
    description: 'Sample command with Progress Bar',
)]
class SampleProgressBarCommand extends Command
{
    private const DEFAULT_MAX_PROGRESS = 100;
    private const DEFAULT_SLEEP_VALUE = 50;

    protected function configure(): void
    {
        $this
            ->addArgument('maxProgress', InputArgument::OPTIONAL, 'Final value of progress')
            ->addArgument('sleep', InputArgument::OPTIONAL, 'Command sleep value (ms)')
        ;
    }

    public function __construct(
        private readonly ConsoleArgumentValidator $validator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $maxProgress = (int) ($input->getArgument('maxProgress') ?? self::DEFAULT_MAX_PROGRESS);
        $sleep = (int) ($input->getArgument('sleep') ?? self::DEFAULT_SLEEP_VALUE);

        $this->validateMaxProgressValue($maxProgress);
        $this->validateSleepValue($sleep);

        $progressBar = new ProgressBar($output, $maxProgress);
        $progressBar->start();
        $currentProgress = 0;

        while ($currentProgress < $maxProgress) {
            $this->sleepCommand($sleep);
            $progressBar->advance();
            $currentProgress++;
        }

        $progressBar->finish();
        $output->writeln('');

        return Command::SUCCESS;
    }

    private function sleepCommand(int $sleepMs): void
    {
        usleep($sleepMs * 1000);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateMaxProgressValue(int $maxProgress): void
    {
        $this->validator->handleValidation('maxProgress', $maxProgress, [
            new Assert\Range(['min' => 1, 'max' => 1000 * 1000 * 1000]),
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateSleepValue(int $sleep): void
    {
        $this->validator->handleValidation('sleep', $sleep, [
            new Assert\Range(['min' => 1, 'max' => 10000]),
        ]);
    }
}
