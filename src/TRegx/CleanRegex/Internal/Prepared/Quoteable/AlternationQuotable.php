<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Type;

class AlternationQuotable implements Quoteable
{
    /** @var array */
    private $userInputs;

    public function __construct(array $userInputs)
    {
        $this->userInputs = $userInputs;
    }

    public function quote(string $delimiter): string
    {
        return \implode('|', $this->getQuoted($delimiter));
    }

    private function getQuoted(string $delimiter): array
    {
        return \array_map(function (string $quotable) use ($delimiter) {
            return (new UserInputQuoteable($quotable))->quote($delimiter);
        }, $this->normalizedUserInput());
    }

    private function normalizedUserInput(): array
    {
        foreach ($this->userInputs as $input) {
            $this->validateQuoteable($input);
        }
        return \array_unique($this->userInputEmptyLast());
    }

    private function validateQuoteable($quoteable): void
    {
        if (!\is_string($quoteable)) {
            $type = Type::asString($quoteable);
            throw new InvalidArgumentException("Invalid bound alternate value. Expected string, but $type given");
        }
    }

    private function userInputEmptyLast(): array
    {
        // removes empty strings, and if there was any, appends it to the end
        if (!\in_array('', $this->userInputs)) {
            return $this->userInputs;
        }
        $result = \array_filter($this->userInputs);
        $result[] = '';
        return $result;
    }
}
