<?php
namespace TRegx\CleanRegex\Internal\Prepared\Word;

use Generator;
use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Type\ValueType;

class AlternationWord implements Word
{
    /** @var array */
    private $figures;

    public function __construct(array $figures)
    {
        $this->figures = $figures;
    }

    public function quote(string $delimiter): string
    {
        return '(?:' . \implode('|', \iterator_to_array($this->quotedFigures($delimiter))) . ')';
    }

    private function quotedFigures(string $delimiter): Generator
    {
        foreach ($this->figuresEmptyLast() as $figure) {
            yield $this->word($figure)->quote($delimiter);
        }
    }

    private function figuresEmptyLast(): array
    {
        if (!\in_array('', $this->figures, true)) {
            return $this->figures;
        }
        // removes empty strings, and if there was any, appends it to the end
        $result = \array_filter($this->figures);
        $result[] = '';
        return $result;
    }

    private function word($figure): TextWord
    {
        if (\is_string($figure)) {
            return new TextWord($figure);
        }
        throw InvalidArgument::typeGiven("Invalid bound alternate value. Expected string", new ValueType($figure));
    }
}
