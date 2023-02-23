<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

use TRegx\CleanRegex\Internal\Delimiter\DelimitablePhrase;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

class UnconjugatedPhrase implements Phrase, DelimitablePhrase
{
    /** @var Word */
    private $word;

    public function __construct(Word $word)
    {
        $this->word = $word;
    }

    public function conjugated(string $delimiter): string
    {
        return $this->word->escaped($delimiter);
    }

    public function unconjugated(string $delimiter): string
    {
        return $this->word->escaped($delimiter);
    }
}
