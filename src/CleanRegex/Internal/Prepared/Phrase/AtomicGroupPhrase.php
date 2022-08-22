<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

class AtomicGroupPhrase extends GroupPhrase
{
    protected function phraseGroup(string $phrase): string
    {
        return "(?>$phrase)";
    }
}
