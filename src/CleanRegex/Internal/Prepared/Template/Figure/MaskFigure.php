<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Figure;

use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\KeywordsCondition;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\MaskPhrase;

class MaskFigure implements Figure
{
    /** @var KeywordsCondition */
    private $condition;
    /** @var MaskPhrase */
    private $phrase;

    public function __construct(AutoCapture $autoCapture, string $mask, Flags $flags, array $keywordsAndPatterns)
    {
        $this->condition = new KeywordsCondition($keywordsAndPatterns);
        $this->phrase = new MaskPhrase($autoCapture, $mask, $flags, $keywordsAndPatterns);
    }

    public function suitable(string $candidate): bool
    {
        return $this->condition->suitable($candidate);
    }

    public function phrase(SubpatternFlags $flags): Phrase
    {
        return $this->phrase->phrase($flags);
    }
}
