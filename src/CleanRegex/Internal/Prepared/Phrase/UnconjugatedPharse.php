<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

use TRegx\CleanRegex\Internal\Prepared\Word\Word;

class UnconjugatedPharse extends Phrase
{
    /** @var Word */
    private $word;

    public function __construct(Word $word)
    {
        $this->word = $word;
    }

    public function conjugated(string $delimiter): string
    {
        return $this->word->quoted($delimiter);
    }

    protected function unconjugated(string $delimiter): string
    {
        return $this->word->quoted($delimiter);
    }
}
