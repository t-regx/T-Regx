<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable;

use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\Alternator;
use TRegx\CleanRegex\Internal\ValueType;

class AlternationQuotable implements Quotable
{
    /** @var array */
    private $figures;

    public function __construct(array $figures)
    {
        $this->figures = $figures;
    }

    public function quote(string $delimiter): string
    {
        return Alternator::quote($this->normalizedUserInput(), $delimiter);
    }

    private function normalizedUserInput(): array
    {
        foreach ($this->figures as $figure) {
            $this->validateQuotable($figure);
        }
        return $this->userInputEmptyLast();
    }

    private function validateQuotable($quoteable): void
    {
        if (!\is_string($quoteable)) {
            throw InvalidArgument::typeGiven("Invalid bound alternate value. Expected string", new ValueType($quoteable));
        }
    }

    private function userInputEmptyLast(): array
    {
        // removes empty strings, and if there was any, appends it to the end
        if (!\in_array('', $this->figures)) {
            return $this->figures;
        }
        $result = \array_filter($this->figures);
        $result[] = '';
        return $result;
    }
}
