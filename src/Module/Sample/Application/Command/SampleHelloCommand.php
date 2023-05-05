<?php

namespace App\Module\Sample\Application\Command;

use App\Module\Core\Application\Console\ConsoleArgumentValidator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints as Assert;
use InvalidArgumentException;

#[AsCommand(
    name: 'sample:hello',
    description: 'Hello message',
)]
class SampleHelloCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Your Name', 'World')
        ;
    }

    public function __construct(
        private readonly ConsoleArgumentValidator $validator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getOption('name');
        $this->validateNameValue($name);

        $output->writeln("Hello $name");

        return Command::SUCCESS;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateNameValue(string $name): void
    {
        $this->validator->handleValidation('name', $name, [
            new Assert\Length(['min' => 1, 'max' => 255]),
        ]);
    }
}
