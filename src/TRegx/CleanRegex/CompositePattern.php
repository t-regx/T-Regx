<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Composite\ChainedReplace;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\SafeRegex\preg;

class CompositePattern
{
    /** @var InternalPattern[] */
    private $patterns;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    public function allMatch(string $subject): bool
    {
        foreach ($this->patterns as $pattern) {
            if (!preg::match($pattern->pattern, $subject)) {
                return false;
            }
        }
        return true;
    }

    public function anyMatches(string $subject): bool
    {
        foreach ($this->patterns as $pattern) {
            if (preg::match($pattern->pattern, $subject)) {
                return true;
            }
        }
        return false;
    }

    public function chainedRemove(string $subject): string
    {
        return $this->chainedReplace($subject)->withReferences('');
    }

    public function chainedReplace(string $subject): ChainedReplace
    {
        return new ChainedReplace($this->patterns, $subject, new DefaultStrategy());
    }
}
