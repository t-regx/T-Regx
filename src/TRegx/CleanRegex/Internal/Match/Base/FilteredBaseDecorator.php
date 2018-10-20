<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\Match\Details\MatchFactory;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Match\Details\Match;

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

    public function getSubject(): string
    {
        return $this->base->getSubject();
    }

    public function match(): array
    {
        $matches = $this->base->matchAllOffsets();
        /** @var Match $match */
        foreach (MatchFactory::fromMatchAll($this->base, $matches) as $index => $match) {
            if ($this->predicate->test($match)) {
                $raw = array_map(function (array $match) use ($index) {
                    list($text, $offset) = $match[$index];
                    return $text;
                }, $matches);
                return $raw;
            }
        }
        return [];
    }

    public function matchCountOffset(): array
    {
        $matches = $this->base->matchAllOffsets();
        /** @var Match $match */
        foreach (MatchFactory::fromMatchAll($this->base, $matches) as $index => $match) {
            if ($this->predicate->test($match)) {
                $raw = array_map(function (array $match) use ($index) {
                    return $match[$index];
                }, $matches);
                return [$raw, 1];
            }
        }
        return [[], 0];
    }

    public function matchCountVerified(): array
    {
        return $this->matchCountOffset();
    }

    public function matchAll(): array
    {
        $matchAll = $this->base->matchAllOffsets();
        $filterMatches = $this->filterMatches($matchAll);
        return $this->removeOffsets($filterMatches);
    }

    public function matchAllOffsets(): array
    {
        $matchAllOffsets = $this->base->matchAllOffsets();
        return $this->filterMatches($matchAllOffsets);
    }

    private function filterMatches(array $matchAll): array
    {
        $matches = MatchFactory::fromMatchAll($this->base, $matchAll);

        $filteredMatches = array_filter($matches, [$this->predicate, 'test']);

        return array_map(function (array $match) use ($filteredMatches) {
            return array_values(array_intersect_key($match, $filteredMatches));
        }, $matchAll);
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
