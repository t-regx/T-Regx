<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Stream\GroupStreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\SubjectStreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\GroupNotMatched;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Offset\ByteOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Subject;

class MatchGroupOffsetStream implements Upstream
{
    use ListStream;

    /** @var Base */
    private $base;
    /** @var GroupKey */
    private $group;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Subject */
    private $subject;

    public function __construct(Base $base, Subject $subject, GroupKey $group, MatchAllFactory $allFactory)
    {
        $this->base = $base;
        $this->group = $group;
        $this->allFactory = $allFactory;
        $this->subject = $subject;
    }

    protected function entries(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->group)) {
            throw new NonexistentGroupException($this->group);
        }
        if ($matches->matched()) {
            return \array_map([$this, 'readOffset'], $matches->getGroupTextAndOffsetAll($this->group->nameOrIndex()));
        }
        throw new UnmatchedStreamException();
    }

    private function readOffset($tuple): ?int
    {
        if ($tuple === '') {
            return null;
        }
        if ($tuple === null) {
            return null;
        }
        [$text, $offset] = $tuple;
        if ($offset === -1) {
            return null;
        }
        return ByteOffset::toCharacterOffset($this->subject, $offset);
    }

    protected function firstValue(): int
    {
        $match = $this->base->matchOffset();
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, 0);
        if (!$polyfill->hasGroup($this->group)) {
            throw new NonexistentGroupException($this->group);
        }
        if (!$match->matched()) {
            throw new SubjectStreamRejectedException(new FromFirstMatchOffsetMessage($this->group), $this->subject);
        }
        if (!$polyfill->isGroupMatched($this->group->nameOrIndex())) {
            throw new GroupStreamRejectedException(new GroupNotMatched\FromFirstMatchOffsetMessage($this->group));
        }
        return ByteOffset::toCharacterOffset($this->subject, $match->getGroupByteOffset($this->group->nameOrIndex()));
    }
}
