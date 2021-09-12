<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimiterablePatternException;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Word\PatternWord;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;
use TRegx\CleanRegex\Internal\TrailingBackslash;
use TRegx\CleanRegex\Internal\UnsuitableStringCondition;

class Standard implements Expression
{
    use StrictInterpretation;

    /** @var string */
    private $pattern;
    /** @var Flags */
    private $flags;
    /** @var Candidates */
    private $candidates;

    public function __construct(string $pattern, string $flags)
    {
        $this->pattern = $pattern;
        $this->flags = new Flags($flags);
        $this->candidates = new Candidates(new UnsuitableStringCondition($pattern));
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
            return $this->candidates->delimiter();
        } catch (UndelimiterablePatternException $exception) {
            throw ExplicitDelimiterRequiredException::forStandard($this->pattern);
        }
    }

    protected function flags(): Flags
    {
        return $this->flags;
    }

    protected function undevelopedInput(): string
    {
        return $this->pattern;
    }
}
