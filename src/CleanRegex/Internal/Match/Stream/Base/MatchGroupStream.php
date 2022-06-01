<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupsFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Stream\SubjectStreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchMessage;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Signatures\ArraySignatures;
use TRegx\CleanRegex\Internal\Pcre\Signatures\PerformanceSignatures;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\Group;

class MatchGroupStream implements Upstream
{
    use ListStream;

    /** @var Base */
    private $base;
    /** @var Subject */
    private $subject;
    /** @var GroupAware */
    private $groupAware;
    /** @var GroupKey */
    private $group;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(Base $base, Subject $subject, GroupAware $groupAware, GroupKey $group, MatchAllFactory $factory)
    {
        $this->base = $base;
        $this->subject = $subject;
        $this->groupAware = $groupAware;
        $this->group = $group;
        $this->allFactory = $factory;
    }

    protected function entries(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->group)) {
            throw new NonexistentGroupException($this->group);
        }
        if (!$matches->matched()) {
            throw new UnmatchedStreamException();
        }
        $signatures = new ArraySignatures($matches->getGroupKeys());
        $facade = new GroupsFacade($this->subject,
            new MatchGroupFactoryStrategy(),
            new EagerMatchAllFactory($matches),
            new GroupHandle($signatures),
            $signatures);
        return $facade->createGroups($this->group, $matches);
    }

    protected function firstValue(): Group
    {
        $match = $this->base->matchOffset();
        if (!$match->hasGroup($this->group)) {
            if (!$this->groupAware->hasGroup($this->group)) {
                throw new NonexistentGroupException($this->group);
            }
        }
        if (!$match->matched()) {
            throw new SubjectStreamRejectedException(new FromFirstMatchMessage($this->group), $this->subject);
        }
        $signatures = new PerformanceSignatures($match, $this->groupAware);
        $groupFacade = new GroupFacade($this->subject,
            new MatchGroupFactoryStrategy(),
            $this->allFactory,
            new GroupHandle($signatures), $signatures);
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, 0);
        return $groupFacade->createGroup($this->group, $polyfill, $polyfill);
    }
}
