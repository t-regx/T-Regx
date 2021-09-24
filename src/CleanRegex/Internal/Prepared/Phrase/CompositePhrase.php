<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

use Generator;

class CompositePhrase extends Phrase
{
    /** @var Phrase[] */
    private $phrases;

    public function __construct(array $phrases)
    {
        $this->phrases = $phrases;
    }

    public function conjugated(string $delimiter): string
    {
        return \implode(\array_reverse(\iterator_to_array($this->conjugatedPhrases($delimiter))));
    }

    private function conjugatedPhrases(string $delimiter): Generator
    {
        $firstConjugated = false;
        foreach (\array_reverse($this->phrases) as $phrase) {
            if ($firstConjugated !== false) {
                yield $phrase->unconjugated($delimiter);
            } else {
                $conjugated = $phrase->conjugated($delimiter);
                if ($conjugated !== '') {
                    $firstConjugated = true;
                }
                yield $conjugated;
            }
        }
    }

    protected function unconjugated(string $delimiter): string
    {
        return \implode(\iterator_to_array($this->unconjugatedPhrases($delimiter)));
    }

    private function unconjugatedPhrases(string $delimiter): Generator
    {
        foreach ($this->phrases as $phrase) {
            yield $phrase->unconjugated($delimiter);
        }
    }
}
