<?php

namespace App\Module\Core\Application\Console;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use InvalidArgumentException;

final class ConsoleArgumentValidator
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {}

    /**
     * @param string $argumentName
     * @param mixed $value
     * @param Constraint[] $constraints
     * @throws InvalidArgumentException
     */
    public function handleValidation(string $argumentName, mixed $value, array $constraints): void
    {
        $violations = $this->validate($value, $constraints);
        $this->handleViolations($argumentName, $violations);
    }

    /**
     * @param mixed $value
     * @param Constraint[] $constraints
     * @return ConstraintViolationListInterface
     */
    public function validate(mixed $value, array $constraints): ConstraintViolationListInterface
    {
        return $this->validator->validate($value, $constraints);
    }

    private function handleViolations(string $argumentName, ConstraintViolationListInterface $violations): void
    {
        if ($violations->count() !== 0) {
            throw new InvalidArgumentException("Invalid argument $argumentName: " . $violations->get(0)->getMessage());
        }
    }
}
