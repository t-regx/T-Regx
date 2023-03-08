<?php
namespace TRegx\CleanRegex\Internal\Prepared\Word;

use Generator;
use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Type\ValueType;

class AlterationFigures
{
    /** @var string[] */
    private $figures;

    /**
     * @param string[] $figures
     */
    public function __construct(array $figures)
    {
        $this->figures = $figures;
    }

    /**
     * @return string[]
     */
    public function figures(): array
    {
        return \array_unique(\iterator_to_array($this->stringFigures()));
    }

    /**
     * @return Generator<string>
     */
    private function stringFigures(): Generator
    {
        foreach ($this->figures as $figure) {
            if (\is_string($figure)) {
                yield $figure;
            } else {
                throw InvalidArgument::typeGiven("Invalid bound alternate value. Expected string", new ValueType($figure));
            }
        }
    }
}
