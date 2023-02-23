<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

class AtomicGroupPhrase implements Phrase
{
    use GroupPhrase;

    protected function phraseGroup(string $phrase): string
    {
        return "(?>$phrase)";
    }
}
