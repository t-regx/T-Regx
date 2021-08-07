<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;

class MatchGroupOffsetStream implements Stream
{
    use ListStream;

    /** @var Base */
    private $base;
    /** @var GroupKey */
    private $groupId;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(Base $base, GroupKey $groupId, MatchAllFactory $allFactory)
    {
        $this->base = $base;
        $this->groupId = $groupId;
        $this->allFactory = $allFactory;
    }

    protected function entries(): array
    {
        $matches = $this->base->matchAllOffsets();
        if ($matches->hasGroup($this->groupId->nameOrIndex())) {
            return \array_map([$this, 'readOffset'], $matches->getGroupTextAndOffsetAll($this->groupId->nameOrIndex()));
        }
        throw new NonexistentGroupException($this->groupId);
    }

    private function readOffset($tuple): ?int
    {
        [$text, $offset] = $tuple;
        if ($offset === -1) {
            return null;
        }
        return $offset;
    }

    protected function firstValue(): int
    {
        $match = $this->base->matchOffset();
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, $match->getIndex());
        if (!$polyfill->hasGroup($this->groupId->nameOrIndex())) {
            throw new NonexistentGroupException($this->groupId);
        }
        if (!$match->matched()) {
            throw SubjectNotMatchedException::forFirstGroupOffset($this->base, $this->groupId);
        }
        if (!$polyfill->isGroupMatched($this->groupId->nameOrIndex())) {
            throw GroupNotMatchedException::forFirstOffset($this->base, $this->groupId);
        }
        return $match->getGroupByteOffset($this->groupId->nameOrIndex());
    }
}
