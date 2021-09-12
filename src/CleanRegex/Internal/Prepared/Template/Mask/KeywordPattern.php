<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Mask;

use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Word\PatternWord;
use TRegx\CleanRegex\Internal\TrailingBackslash;
use TRegx\CleanRegex\Internal\UnsuitableStringCondition;

class KeywordPattern
{
    /** @var string */
    private $pattern;
    /** @var Candidates */
    private $candidates;

    public function __construct(string $keywordPattern)
    {
        $this->pattern = $keywordPattern;
        $this->candidates = new Candidates(new UnsuitableStringCondition($keywordPattern));
    }

    public function valid(): bool
    {
        if (TrailingBackslash::hasTrailingSlash($this->pattern)) {
            return false;
        }
        return $this->definition()->valid();
    }

    private function definition(): Definition
    {
        return new Definition($this->candidates->delimiter()->delimited(new PatternWord($this->pattern), new Flags('')), $this->pattern);
    }
}
