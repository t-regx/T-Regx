<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacadeMatched;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupHandle;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Stream\RejectedOptional;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchMessage;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;
use TRegx\CleanRegex\Internal\Pcre\Signatures\PerformanceSignatures;
use TRegx\CleanRegex\Match\Optional;

class GroupMatchFindFirst
{
    /** @var Base */
    private $base;
    /** @var GroupAware */
    private $groupAware;
    /** @var GroupKey */
    private $group;
    /** @var Subject */
    private $subject;

    public function __construct(Base $base, Subject $subject, GroupAware $groupAware, GroupKey $group)
    {
        $this->base = $base;
        $this->groupAware = $groupAware;
        $this->group = $group;
        $this->subject = $subject;
    }

    public function getOptionalForGroup(callable $consumer): Optional
    {
        $first = $this->base->matchOffset();
        if ($this->matched($first)) {
            return $this->matchedOptional($first, $consumer);
        }
        if ($this->groupAware->hasGroup($this->group)) {
            return $this->notMatchedOptional($first);
        }
        throw new NonexistentGroupException($this->group);
    }

    private function matched(RawMatchOffset $first): bool
    {
        return $first->hasGroup($this->group) && $first->getGroup($this->group->nameOrIndex()) !== null;
    }

    private function matchedOptional(RawMatchOffset $match, callable $consumer): PresentOptional
    {
        $signatures = new PerformanceSignatures($match, $this->groupAware);
        $facade = new GroupFacadeMatched($this->subject,
            new MatchGroupFactoryStrategy(),
            new LazyMatchAllFactory($this->base),
            new GroupHandle($signatures),
            $signatures);
        $false = new FalseNegative($match);
        return new PresentOptional($consumer($facade->createGroup($this->group, $false, $false)));
    }

    private function notMatchedOptional(RawMatchOffset $first): Optional
    {
        if ($first->matched()) {
            return GroupEmptyOptional::forFirst($this->group);
        }
        return new RejectedOptional(new SubjectNotMatchedException(new FromFirstMatchMessage($this->group), $this->subject));
    }
}
