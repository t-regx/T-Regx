<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class DetailPredicateBaseDecorator implements Base
{
    /** @var Base */
    private $base;
    /** @var Predicate */
    private $predicate;
    /** @var DetailObjectFactory */
    private $detailFactory;

    public function __construct(Base $base, Predicate $predicate)
    {
        $this->base = $base;
        $this->predicate = $predicate;
        $this->detailFactory = new DetailObjectFactory($this->base, $this->base->getUserData());
    }

    public function getPattern(): Definition
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
        foreach ($this->detailFactory->mapToDetailObjects($matches) as $index => $match) {
            if ($this->predicate->test($match)) {
                return $matches->getRawMatch($index);
            }
        }
        return new RawMatch([]);
    }

    public function matchOffset(): RawMatchOffset
    {
        $matches = $this->base->matchAllOffsets();
        foreach ($this->detailFactory->mapToDetailObjects($matches) as $index => $match) {
            if ($this->predicate->test($match)) {
                return $matches->getRawMatchOffset($index);
            }
        }
        return new RawMatchOffset([], null);
    }

    public function matchAll(): RawMatches
    {
        return new RawMatches($this->removeOffsets($this->detailFactory->mapToDetailObjectsFiltered($this->base->matchAllOffsets(), $this->predicate)));
    }

    public function matchAllOffsets(): RawMatchesOffset
    {
        return new RawMatchesOffset($this->detailFactory->mapToDetailObjectsFiltered($this->base->matchAllOffsets(), $this->predicate));
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
