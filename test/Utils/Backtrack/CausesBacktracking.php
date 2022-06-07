<?php
namespace Test\Utils\Backtrack;

use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Match\Search;
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
        return $this->backtrackingPattern()->match($this->backtrackingSubject(1));
    }

    public function backtrackingSearch(): Search
    {
        return $this->backtrackingPattern()->search($this->backtrackingSubject(1));
    }

    private function backtrackingReplace(): ReplaceLimit
    {
        return $this->backtrackingPattern()->replace($this->backtrackingSubject(1));
    }

    public function backtrackingPattern(): Pattern
    {
        return Pattern::of('(\d+\d+)+3');
    }

    public function backtrackingSubject(int $index): string
    {
        $simpleMatches = str_repeat('123 ', $index);
        $hardMatch = '11111111111111111111';
        return "€, $simpleMatches, $hardMatch 3";
    }
}
