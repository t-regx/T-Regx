<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Match\FlatMap;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\Match\IndexedRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class FlatMapStrategy implements Strategy
{
    /** @var callable */
    private $mapper;
    /** @var FlatMapStrategy */
    private $strategy;
    /** @var DetailObjectFactory */
    private $factory;

    public function __construct(callable $mapper, FlatMap\FlatMapStrategy $strategy, DetailObjectFactory $factory)
    {
        $this->mapper = $mapper;
        $this->strategy = $strategy;
        $this->factory = $factory;
    }

    public function transform(array $groups, RawMatchesOffset $matches): array
    {
        $closure = function (IndexedRawMatchOffset $match) use ($matches) {
            $mapper = $this->mapper;
            return $mapper($this->factory->create($match->getIndex(), $match, new EagerMatchAllFactory($matches)));
        };
        foreach ($groups as &$group) {
            $group = (new FlatMapper($group, $this->strategy, $closure))->get();
        }
        return $groups;
    }
}
