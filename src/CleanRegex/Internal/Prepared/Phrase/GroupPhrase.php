<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

trait GroupPhrase
{
    /** @var Phrase */
    private $phrase;

    public function __construct(Phrase $phrase)
    {
        $this->phrase = $phrase;
    }

    public function conjugated(string $delimiter): string
    {
        return $this->phraseGroup($this->phrase->unconjugated($delimiter));
    }

    public function unconjugated(string $delimiter): string
    {
        return $this->phraseGroup($this->phrase->unconjugated($delimiter));
    }

    abstract protected function phraseGroup(string $phrase): string;
}
