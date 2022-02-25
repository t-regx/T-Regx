<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

use Generator;

class CompositePhrase implements Phrase
{
    /** @var Phrase[] */
    private $phrases;

    public function __construct(array $phrases)
    {
        $this->phrases = $phrases;
    }

    public function conjugated(string $delimiter): string
    {
        $conjugation = new IdempotentConjugation($delimiter);
        $conjugated = '';
        foreach ($this->reversedPhrases() as $phrase) {
            $conjugated = $conjugation->conjugatedOnce($phrase) . $conjugated;
        }
        return $conjugated;
    }

    private function reversedPhrases(): Generator
    {
        for (\end($this->phrases); \key($this->phrases) !== null; \prev($this->phrases)) {
            yield \current($this->phrases);
        }
    }

    public function unconjugated(string $delimiter): string
    {
        $unconjugated = '';
        foreach ($this->phrases as $phrase) {
            $unconjugated .= $phrase->unconjugated($delimiter);
        }
        return $unconjugated;
    }
}
