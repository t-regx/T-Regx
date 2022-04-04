<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Numeral\GroupExceptions;
use TRegx\CleanRegex\Internal\Match\Numeral\IntegerBase;
use TRegx\CleanRegex\Internal\Match\Stream\GroupStreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\ListStream;
use TRegx\CleanRegex\Internal\Match\Stream\SubjectStreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\GroupNotMatched;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchIntMessage;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Numeral;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
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
        if (!$matches->hasGroup($this->group)) {
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
        if (!$polyfill->hasGroup($this->group)) {
            throw new NonexistentGroupException($this->group);
        }
        if (!$match->matched()) {
            throw new SubjectStreamRejectedException(new FromFirstMatchIntMessage($this->group), $this->subject);
        }
        if (!$polyfill->isGroupMatched($this->group->nameOrIndex())) {
            throw new GroupStreamRejectedException(new GroupNotMatched\FromFirstMatchIntMessage($this->group));
        }
        return $this->numberBase->integer($match->getGroup($this->group->nameOrIndex()));
    }
}
