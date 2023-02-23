<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

class NonCaptureGroupPhrase implements Phrase
{
    use GroupPhrase;

    protected function phraseGroup(string $phrase): string
    {
        return "(?:$phrase)";
    }
}
