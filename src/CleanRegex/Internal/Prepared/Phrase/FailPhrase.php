<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

use TRegx\CleanRegex\Internal\Delimiter\DelimitablePhrase;

class FailPhrase implements Phrase, DelimitablePhrase
{
    public function conjugated(string $delimiter): string
    {
        return '(*FAIL)';
    }

    public function unconjugated(string $delimiter): string
    {
        return '(?>(*FAIL))';
    }
}
