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
        return \implode(\iterator_to_array($this->conjugatedPhrases($delimiter)));
    }

    private function conjugatedPhrases(string $delimiter): Generator
    {
        foreach ($this->phrases as $key => $phrase) {
            if ($key === \count($this->phrases) - 1) {
                yield $phrase->conjugated($delimiter);
            } else {
                yield $phrase->unconjugated($delimiter);
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
