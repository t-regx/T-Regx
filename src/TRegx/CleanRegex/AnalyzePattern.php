<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Analyze\Simplify\PatternSimplifier;
use TRegx\CleanRegex\Internal\InternalPattern;

class AnalyzePattern
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function simplify(): string
    {
        return (new PatternSimplifier($this->pattern))->simplify();
    }
}
