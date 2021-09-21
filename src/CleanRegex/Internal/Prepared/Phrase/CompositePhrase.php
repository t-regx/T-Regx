<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

use Generator;

class CompositePhrase implements Phrase
{
    /** @var Phrase[] */
    private $words;

    public function __construct(array $words)
    {
        $this->words = $words;
    }

    public function quoted(string $delimiter): string
    {
        return \implode(\iterator_to_array($this->quotedWords($delimiter)));
    }

    private function quotedWords(string $delimiter): Generator
    {
        foreach ($this->words as $word) {
            yield $word->quoted($delimiter);
        }
    }
}
