<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class IgnoreBaseDecorator implements Base
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
        foreach ($matches->getDetailObjects($this->getDetailFactory()) as $index => $match) {
            if ($this->predicate->test($match)) {
                return $matches->getRawMatch($index);
            }
        }
        return new RawMatch([]);
    }

    public function matchOffset(): RawMatchOffset
    {
        $matches = $this->base->matchAllOffsets();
        $matchObjects = $matches->getDetailObjects($this->getDetailFactory());
        foreach ($matchObjects as $index => $match) {
            if ($this->predicate->test($match)) {
                return $matches->getRawMatchOffset($index);
            }
        }
        return new RawMatchOffset([], null);
    }

    private function getDetailFactory(): DetailObjectFactory
    {
        return new DetailObjectFactory($this->base, -1, $this->base->getUserData());
    }

    public function matchAll(): RawMatches
    {
        return new RawMatches($this->removeOffsets($this->base->matchAllOffsets()->filterMatchesByDetailObjects($this->predicate, $this->getDetailFactory())));
    }

    public function matchAllOffsets(): RawMatchesOffset
    {
        return new RawMatchesOffset($this->base->matchAllOffsets()->filterMatchesByDetailObjects($this->predicate, $this->getDetailFactory()));
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

    public function getUnfilteredBase(): Base
    {
        return $this->base->getUnfilteredBase();
    }
}
