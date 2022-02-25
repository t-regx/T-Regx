<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

/**
 * Dictionary definition
 * <i>Idempotent</i> - activity such that doing it once, has the same effect as doing it multiple times
 */
class IdempotentConjugation
{
    /** @var string */
    private $delimiter;
    /** @var bool */
    private $alreadyConjugated = false;

    public function __construct(string $delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function conjugatedOnce(Phrase $phrase): string
    {
        if ($this->alreadyConjugated) {
            return $phrase->unconjugated($this->delimiter);
        }
        return $this->conjugated($phrase);
    }

    private function conjugated(Phrase $phrase): string
    {
        $conjugated = $phrase->conjugated($this->delimiter);
        if ($conjugated === '') {
            return '';
        }
        $this->alreadyConjugated = true;
        return $conjugated;
    }
}
