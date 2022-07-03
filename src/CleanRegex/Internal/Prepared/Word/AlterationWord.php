<?php
namespace TRegx\CleanRegex\Internal\Prepared\Word;

use Generator;

class AlterationWord implements Word
{
    /** @var AlterationFigures */
    private $figures;

    public function __construct(array $figures)
    {
        $this->figures = new AlterationFigures($figures);
    }

    public function escaped(string $delimiter): string
    {
        return \implode('|', \iterator_to_array($this->escapedFigures($delimiter)));
    }

    private function escapedFigures(string $delimiter): Generator
    {
        foreach ($this->figures->figures() as $figure) {
            yield (new TextWord($figure))->escaped($delimiter);
        }
    }
}
