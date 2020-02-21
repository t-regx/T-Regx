<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

class FlatMapStrategy implements Strategy
{
    /** @var callable */
    private $mapper;

    public function __construct(callable $mapper)
    {
        $this->mapper = $mapper;
    }

    function transform(array $groups, IRawMatchesOffset $matches): array
    {
        return $groups;
    }
}
