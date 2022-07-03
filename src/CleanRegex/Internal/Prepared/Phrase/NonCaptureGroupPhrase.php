<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

class NonCaptureGroupPhrase implements Phrase
{
    /** @var Phrase */
    private $phrase;

    public function __construct(Phrase $phrase)
    {
        $this->phrase = $phrase;
    }

    public function conjugated(string $delimiter): string
    {
        return $this->phraseGroup($delimiter);
    }

    public function unconjugated(string $delimiter): string
    {
        return $this->phraseGroup($delimiter);
    }

    private function phraseGroup(string $delimiter): string
    {
        $phrase = $this->phrase->unconjugated($delimiter);
        return "(?:$phrase)";
    }
}
