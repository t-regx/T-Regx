<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Figure;

use TRegx\CleanRegex\Internal\Prepared\Pattern\EmptyFlagPattern;
use TRegx\CleanRegex\Internal\Prepared\PatternAsEntities;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\LiteralPlaceholders;
use TRegx\CleanRegex\Internal\Prepared\Template\DelimiterAware;

class PatternFigure implements Figure
{
    use DelimiterAware;

    /** @var PatternAsEntities */
    private $patternAsEntities;
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->patternAsEntities = new PatternAsEntities(new EmptyFlagPattern($pattern), new LiteralPlaceholders());
        $this->pattern = $pattern;
    }

    public function phrase(): Phrase
    {
        return $this->patternAsEntities->phrase();
    }

    protected function delimiterAware(): string
    {
        return $this->pattern;
    }
}
