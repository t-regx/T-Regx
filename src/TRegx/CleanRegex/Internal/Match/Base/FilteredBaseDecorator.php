<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\MatchObjectFactory;

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

    public function match(): RawMatch
    {
        $matches = $this->base->matchAllOffsets();
        foreach ($matches->getMatchObjects($this->getMatchFactory()) as $index => $match) {
            if ($this->predicate->test($match)) {
                return $matches->getRawMatch($index);
            }
        }
        return new RawMatch([]);
    }

    public function matchOffset(): RawMatchOffset
    {
        $matches = $this->base->matchAllOffsets();
        $matchObjects = $matches->getMatchObjects($this->getMatchFactory());
        foreach ($matchObjects as $index => $match) {
            if ($this->predicate->test($match)) {
                return $matches->getRawMatchOffset($index);
            }
        }
        return new RawMatchOffset([]);
    }

    private function getMatchFactory(): MatchObjectFactory
    {
        return new MatchObjectFactory($this->base, -1, $this->base->getUserData());
    }

    public function matchAll(): RawMatches
    {
        $filterMatches = $this->base->matchAllOffsets()->filterMatchesByMatchObjects($this->predicate, $this->getMatchFactory());
        return new RawMatches($this->removeOffsets($filterMatches));
    }

    public function matchAllOffsets(): RawMatchesOffset
    {
        return new RawMatchesOffset($this->base->matchAllOffsets()->filterMatchesByMatchObjects($this->predicate, $this->getMatchFactory()));
    }

    private function removeOffsets(array $filterMatches): array
    {
        return \array_map(static function (array $matches): array {
            return \array_map(static function ($match): ?string {
                if ($match === null || $match === '') {
                    return null;
                }
                [$text, $offset] = $match;
                return $text;
            }, $matches);
        }, $filterMatches);
    }

    public function getUserData(): UserData
    {
        return $this->base->getUserData();
    }
}
