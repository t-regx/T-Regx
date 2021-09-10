<?php
namespace TRegx\CleanRegex\Internal\Prepared\Word;

use Generator;

class CompositeWord implements Word
{
    /** @var Word[] */
    private $words;

    public function __construct(array $words)
    {
        $this->words = $words;
    }

    public function quote(string $delimiter): string
    {
        return \implode(\iterator_to_array($this->quotedWords($delimiter)));
    }

    private function quotedWords(string $delimiter): Generator
    {
        foreach ($this->words as $word) {
            yield $word->quote($delimiter);
        }
    }
}
