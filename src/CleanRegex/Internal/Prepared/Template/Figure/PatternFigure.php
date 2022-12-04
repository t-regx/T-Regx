<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Figure;

use TRegx\CleanRegex\Internal\AutoCapture\Group\GroupAutoCapture;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Pattern\SubpatternFlagsStringPattern;
use TRegx\CleanRegex\Internal\Prepared\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\LiteralPlaceholders;
use TRegx\CleanRegex\Internal\Prepared\Template\DelimiterAware;

class PatternFigure implements Figure
{
    use DelimiterAware;

    /** @var GroupAutoCapture */
    private $autoCapture;
    /** @var string */
    private $pattern;

    public function __construct(GroupAutoCapture $autoCapture, string $pattern)
    {
        $this->autoCapture = $autoCapture;
        $this->pattern = $pattern;
    }

    public function phrase(SubpatternFlags $flags): Phrase
    {
        $patternPhrase = new PatternPhrase($this->autoCapture, new SubpatternFlagsStringPattern($this->pattern, $flags), new LiteralPlaceholders());
        return $patternPhrase->phrase();
    }

    protected function delimiterAware(): string
    {
        return $this->pattern;
    }
}
