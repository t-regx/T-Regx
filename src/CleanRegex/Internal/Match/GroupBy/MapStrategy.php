<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\Match\IndexedRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class MapStrategy implements Strategy
{
    /** @var callable */
    private $mapper;
    /** @var DetailObjectFactory */
    private $factory;

    public function __construct(callable $mapper, DetailObjectFactory $factory)
    {
        $this->mapper = $mapper;
        $this->factory = $factory;
    }

    public function transform(array $groups, RawMatchesOffset $matches): array
    {
        foreach ($groups as &$group) {
            /** @var IndexedRawMatchOffset $match */
            foreach ($group as &$match) {
                $mapper = $this->mapper;
                $match = $mapper($this->factory->create($match->getIndex(), $match, new EagerMatchAllFactory($matches)));
            }
        }
        return $groups;
    }
}
