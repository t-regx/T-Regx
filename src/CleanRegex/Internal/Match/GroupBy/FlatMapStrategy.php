<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Match\FlatMap;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class FlatMapStrategy implements Strategy
{
    /** @var callable */
    private $mapper;
    /** @var FlatMapStrategy */
    private $strategy;
    /** @var DetailObjectFactory */
    private $factory;
    /** @var string */
    private $methodName;

    public function __construct(callable $mapper, FlatMap\FlatMapStrategy $strategy, DetailObjectFactory $factory, string $methodName)
    {
        $this->mapper = $mapper;
        $this->strategy = $strategy;
        $this->factory = $factory;
        $this->methodName = $methodName;
    }

    public function transform(array $groups, RawMatchesOffset $matches): array
    {
        $closure = function (RawMatchOffset $match) use ($matches) {
            $mapper = $this->mapper;
            return $mapper($this->factory->create($match->getIndex(), $match, new EagerMatchAllFactory($matches)));
        };
        foreach ($groups as &$group) {
            $group = (new FlatMapper($this->strategy, $closure, $this->methodName))->get($group);
        }
        return $groups;
    }
}
