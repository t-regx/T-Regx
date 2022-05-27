<?php
namespace Test\Utils;

use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\Replace\ReplaceLimit;

/**
 * This pattern and subject are deliberately created to
 * produce {@see CatastrophicBacktrackingException}, if they
 * are called more than once. That way, we can test
 * whether "first" method really tries to search the first
 * occurrence.
 */
trait CausesBacktracking
{
    public function backtrackingMatch(): MatchPattern
    {
        return $this->backtrackingPattern()->match($this->backtrackingSubject());
    }

    private function backtrackingReplace(): ReplaceLimit
    {
        return $this->backtrackingPattern()->replace($this->backtrackingSubject());
    }

    public function backtrackingPattern(): Pattern
    {
        return Pattern::of('(\d+\d+)+3');
    }

    public function backtrackingSubject(): string
    {
        return '€, 123, 11111111111111111111, 3';
    }
}
