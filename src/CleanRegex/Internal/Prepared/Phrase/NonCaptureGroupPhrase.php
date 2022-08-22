<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

class NonCaptureGroupPhrase extends GroupPhrase
{
    protected function phraseGroup(string $phrase): string
    {
        return "(?:$phrase)";
    }
}
