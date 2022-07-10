<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Figure;

use TRegx\CleanRegex\Internal\Prepared\Pattern\EmptyFlagPattern;
use TRegx\CleanRegex\Internal\Prepared\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\LiteralPlaceholders;
use TRegx\CleanRegex\Internal\Prepared\Template\DelimiterAware;

class PatternFigure implements Figure
{
    use DelimiterAware;

    /** @var PatternPhrase */
    private $patternPhrase;
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->patternPhrase = new PatternPhrase(new EmptyFlagPattern($pattern), new LiteralPlaceholders());
        $this->pattern = $pattern;
    }

    public function phrase(): Phrase
    {
        return $this->patternPhrase->phrase();
    }

    protected function delimiterAware(): string
    {
        return $this->pattern;
    }
}
