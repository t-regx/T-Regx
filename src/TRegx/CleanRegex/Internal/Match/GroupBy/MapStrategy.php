<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\IndexedRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\MatchObjectFactory;

class MapStrategy implements Strategy
{
    /** @var callable */
    private $mapper;
    /** @var MatchObjectFactory */
    private $factory;

    public function __construct(callable $mapper, MatchObjectFactory $factory)
    {
        $this->mapper = $mapper;
        $this->factory = $factory;
    }

    function transform(array $groups, IRawMatchesOffset $matches): array
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
