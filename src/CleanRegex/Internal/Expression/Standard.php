<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimiterablePatternException;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Word\PatternWord;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;
use TRegx\CleanRegex\Internal\TrailingBackslash;

class Standard implements Expression
{
    use StrictInterpretation;

    /** @var string */
    private $pattern;
    /** @var string */
    private $flags;

    public function __construct(string $pattern, string $flags)
    {
        $this->pattern = $pattern;
        $this->flags = $flags;
    }

    protected function word(): Word
    {
        if (TrailingBackslash::hasTrailingSlash($this->pattern)) {
            throw new PatternMalformedPatternException('Pattern may not end with a trailing backslash');
        }
        return new PatternWord($this->pattern);
    }

    protected function delimiter(): Delimiter
    {
        try {
            return Delimiter::suitable($this->pattern);
        } catch (UndelimiterablePatternException $exception) {
            throw ExplicitDelimiterRequiredException::forStandard($this->pattern);
        }
    }

    protected function flags(): Flags
    {
        return new Flags($this->flags);
    }

    protected function undevelopedInput(): string
    {
        return $this->pattern;
    }
}
