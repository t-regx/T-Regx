<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchGroupable;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

class FilteredBaseDecorator implements Base
{
    /** @var Base */
    private $base;
    /** @var Predicate */
    private $predicate;

    public function __construct(Base $base, Predicate $predicate)
    {
        $this->base = $base;
        $this->predicate = $predicate;
    }

    public function getPattern(): Pattern
    {
        return $this->base->getPattern();
    }

    public function getApiBase(): ApiBase
    {
        return $this->base->getApiBase();
    }

    public function getSubject(): string
    {
        return $this->base->getSubject();
    }

    public function match(): RawMatch
    {
        $matches = $this->base->matchAllOffsets();
        foreach ($matches->getMatchObjects() as $index => $match) {
            if ($this->predicate->test($match)) {
                return $matches->getRawMatch($index);
            }
        }
        return new RawMatch([]);
    }

    public function matchOffset(): RawMatchOffset
    {
        $matches = $this->base->matchAllOffsets();
        foreach ($matches->getMatchObjects() as $index => $match) {
            if ($this->predicate->test($match)) {
                return $matches->getRawMatchOffset($index);
            }
        }
        return new RawMatchOffset([]);
    }

    public function matchGroupable(): IRawMatchGroupable
    {
        return $this->matchOffset();
    }

    public function matchAll(): RawMatches
    {
        $filterMatches = $this->base->matchAllOffsets()->filterMatchesByMatchObjects($this->predicate);
        $values = $this->removeOffsets($filterMatches);
        return new RawMatches($values);
    }

    public function matchAllOffsets(): IRawMatchesOffset
    {
        $matches = $this->base->matchAllOffsets()->filterMatchesByMatchObjects($this->predicate);
        return new RawMatchesOffset($matches, $this->base);
    }

    private function removeOffsets(array $filterMatches): array
    {
        return array_map(function (array $matches) {
            return array_map(function ($match) {
                list($text, $offset) = $match;
                return $text;
            }, $matches);
        }, $filterMatches);
    }
}
