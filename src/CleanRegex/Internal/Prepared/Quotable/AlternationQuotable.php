<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable;

use TRegx\CleanRegex\Internal\InvalidArgument;
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
        return '(?:' . \implode('|', $this->quotedFigures($delimiter)) . ')';
    }

    private function quotedFigures(string $delimiter): array
    {
        $result = [];
        foreach ($this->figuresEmptyLast() as $input) {
            $result[] = $this->quotable($input)->quote($delimiter);
        }
        return $result;
    }

    private function figuresEmptyLast(): array
    {
        // removes empty strings, and if there was any, appends it to the end
        if (!\in_array('', $this->figures, true)) {
            return $this->figures;
        }
        $result = \array_filter($this->figures);
        $result[] = '';
        return $result;
    }

    private function quotable($input): UserInputQuotable
    {
        if (\is_string($input)) {
            return new UserInputQuotable($input);
        }
        throw InvalidArgument::typeGiven("Invalid bound alternate value. Expected string", new ValueType($input));
    }
}
