<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Figure;

use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\KeywordsCondition;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\MaskPhrase;

class MaskFigure implements Figure
{
    /** @var KeywordsCondition */
    private $condition;
    /** @var MaskPhrase */
    private $phrase;

    public function __construct(string $mask, Flags $flags, array $keywordsAndPatterns)
    {
        $this->condition = new KeywordsCondition($keywordsAndPatterns);
        $this->phrase = new MaskPhrase($mask, $flags, $keywordsAndPatterns);
    }

    public function suitable(string $candidate): bool
    {
        return $this->condition->suitable($candidate);
    }

    public function phrase(): Phrase
    {
        return $this->phrase->phrase();
    }
}
