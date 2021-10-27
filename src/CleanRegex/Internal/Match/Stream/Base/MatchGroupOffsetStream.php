<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Stream\ListStream;
use TRegx\CleanRegex\Internal\Match\Stream\StramRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\GroupNotMatched;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;

class MatchGroupOffsetStream implements Upstream
{
    use ListStream;

    /** @var Base */
    private $base;
    /** @var GroupKey */
    private $group;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(Base $base, GroupKey $group, MatchAllFactory $allFactory)
    {
        $this->base = $base;
        $this->group = $group;
        $this->allFactory = $allFactory;
    }

    protected function entries(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->group->nameOrIndex())) {
            throw new NonexistentGroupException($this->group);
        }
        if ($matches->matched()) {
            return \array_map([$this, 'readOffset'], $matches->getGroupTextAndOffsetAll($this->group->nameOrIndex()));
        }
        throw new UnmatchedStreamException();
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
        if (!$polyfill->hasGroup($this->group->nameOrIndex())) {
            throw new NonexistentGroupException($this->group);
        }
        if (!$match->matched()) {
            throw new StramRejectedException($this->base, SubjectNotMatchedException::class, new FromFirstMatchOffsetMessage($this->group));
        }
        if (!$polyfill->isGroupMatched($this->group->nameOrIndex())) {
            throw new StramRejectedException($this->base, GroupNotMatchedException::class, new GroupNotMatched\FromFirstMatchOffsetMessage($this->group));
        }
        return $match->getGroupByteOffset($this->group->nameOrIndex());
    }
}
