<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

class ConjugatedPhrase extends Phrase
{
    /** @var string */
    private $conjugated;
    /** @var string */
    private $phrase;

    public function __construct(string $conjugated, string $phrase)
    {
        $this->conjugated = $conjugated;
        $this->phrase = $phrase;
    }

    public function conjugated(string $delimiter): string
    {
        return $this->conjugated;
    }

    protected function unconjugated(string $delimiter): string
    {
        return $this->phrase;
    }
}
