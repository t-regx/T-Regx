<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Numeral\GroupExceptions;
use TRegx\CleanRegex\Internal\Match\Numeral\IntegerBase;
use TRegx\CleanRegex\Internal\Match\Stream\ListStream;
use TRegx\CleanRegex\Internal\Match\Stream\StreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\GroupNotMatched;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchIntMessage;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Numeral;
use TRegx\CleanRegex\Internal\Subject;

class MatchGroupIntStream implements Upstream
{
    use ListStream;

    /** @var Base */
    private $base;
    /** @var Subject */
    private $subject;
    /** @var GroupKey */
    private $group;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var IntegerBase */
    private $numberBase;

    public function __construct(Base $base, Subject $subject, GroupKey $group, MatchAllFactory $allFactory, Numeral\Base $numberBase)
    {
        $this->base = $base;
        $this->subject = $subject;
        $this->group = $group;
        $this->allFactory = $allFactory;
        $this->numberBase = new IntegerBase($numberBase, new GroupExceptions($this->group));
    }

    protected function entries(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->group->nameOrIndex())) {
            throw new NonexistentGroupException($this->group);
        }
        if ($matches->matched()) {
            return \array_map([$this, 'parseIntegerOptional'], $matches->getGroupTexts($this->group->nameOrIndex()));
        }
        throw new UnmatchedStreamException();
    }

    private function parseIntegerOptional(?string $text): ?int
    {
        if ($text === null) {
            return null;
        }
        return $this->numberBase->integer($text);
    }

    protected function firstValue(): int
    {
        $match = $this->base->matchOffset();
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, 0);
        if (!$polyfill->hasGroup($this->group->nameOrIndex())) {
            throw new NonexistentGroupException($this->group);
        }
        if (!$match->matched()) {
            throw new StreamRejectedException($this->subject, SubjectNotMatchedException::class, new FromFirstMatchIntMessage($this->group));
        }
        if (!$polyfill->isGroupMatched($this->group->nameOrIndex())) {
            throw new StreamRejectedException($this->subject, GroupNotMatchedException::class, new GroupNotMatched\FromFirstMatchIntMessage($this->group));
        }
        return $this->numberBase->integer($match->getGroup($this->group->nameOrIndex()));
    }
}
